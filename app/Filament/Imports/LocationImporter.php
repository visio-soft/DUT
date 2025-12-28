<?php

namespace App\Filament\Imports;

use App\Models\City;
use App\Models\Country;
use App\Models\District;
use App\Models\Neighborhood;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LocationImporter extends Importer
{
    protected static ?string $model = Neighborhood::class;

    protected ?Country $country = null;

    protected ?City $city = null;

    protected ?District $district = null;

    protected ?Neighborhood $neighborhood = null;

    protected ?string $targetLevel = null;

    protected bool $rowPrepared = false;

    /**
     * @var array<string, Country>
     */
    protected array $countryCache = [];

    /**
     * @var array<string, City>
     */
    protected array $cityCache = [];

    /**
     * @var array<string, District>
     */
    protected array $districtCache = [];

    /**
     * @var array<string, Neighborhood>
     */
    protected array $neighborhoodCache = [];

    /**
     * @return array<ImportColumn>
     */
    public static function getColumns(): array
    {
        return [
            ImportColumn::make('country')
                ->label(__('common.location_import_column_country'))
                ->example('Türkiye')
                ->rules(['nullable', 'string', 'max:255']),
            ImportColumn::make('city')
                ->label(__('common.location_import_column_city'))
                ->example('İstanbul')
                ->rules(['nullable', 'string', 'max:255']),
            ImportColumn::make('district')
                ->label(__('common.location_import_column_district'))
                ->example('Sultangazi')
                ->rules(['nullable', 'string', 'max:255']),
            ImportColumn::make('neighborhood')
                ->label(__('common.location_import_column_neighborhood'))
                ->example('50. Yıl Mahallesi')
                ->rules(['nullable', 'string', 'max:255']),
        ];
    }

    public function resolveRecord(): ?Model
    {
        $this->resetRowContext();
        $this->prepareRow();

        $this->country = $this->ensureCountry($this->data['country']);

        if (filled($this->data['city'])) {
            $this->city = $this->ensureCity($this->country, $this->data['city']);
        }

        if (filled($this->data['district'])) {
            $this->district = $this->ensureDistrict($this->city, $this->data['district']);
        }

        if ($this->targetLevel === 'country') {
            return $this->country;
        }

        if ($this->targetLevel === 'city') {
            return $this->city;
        }

        if ($this->targetLevel === 'district') {
            return $this->district;
        }

        $this->neighborhood = $this->ensureNeighborhood($this->district, $this->data['neighborhood']);

        return $this->neighborhood;
    }

    public function fillRecord(): void
    {
        if (! $this->rowPrepared) {
            $this->prepareRow();
        }

        if ($this->record instanceof Country) {
            $this->record->name = $this->data['country'];

            return;
        }

        if ($this->record instanceof City) {
            $this->record->name = $this->data['city'];
            $this->record->country()->associate($this->country);

            return;
        }

        if ($this->record instanceof District) {
            $this->record->name = $this->data['district'];
            $this->record->city()->associate($this->city);

            return;
        }

        if ($this->record instanceof Neighborhood) {
            $this->record->name = $this->data['neighborhood'];
            $this->record->district()->associate($this->district);
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $successfulRows = $import->successful_rows;
        $failedRows = $import->getFailedRowsCount();

        return __('common.location_import_result', [
            'success' => $successfulRows,
            'failed' => $failedRows,
        ]);
    }

    public static function getCompletedNotificationTitle(Import $import): string
    {
        $failedRows = $import->getFailedRowsCount();

        if ($failedRows > 0) {
            return __('common.location_import_completed_with_errors');
        }

        return __('common.location_import_completed');
    }

    /**
     * @throws ValidationException
     */
    protected function prepareRow(): void
    {
        if ($this->rowPrepared) {
            return;
        }

        $this->data['country'] = $this->normalizeValue($this->data['country'] ?? null);
        $this->data['city'] = $this->normalizeValue($this->data['city'] ?? null);
        $this->data['district'] = $this->normalizeValue($this->data['district'] ?? null);
        $this->data['neighborhood'] = $this->normalizeValue($this->data['neighborhood'] ?? null);

        if ($this->allFieldsBlank()) {
            throw ValidationException::withMessages([
                'country' => __('common.location_import_row_empty'),
            ]);
        }

        $this->validateHierarchy();
        $this->targetLevel = $this->determineTargetLevel();
        $this->rowPrepared = true;
    }

    /**
     * @throws ValidationException
     */
    protected function validateHierarchy(): void
    {
        $this->assertParentPresence('city', 'country');
        $this->assertParentPresence('district', 'city');
        $this->assertParentPresence('neighborhood', 'district');
    }

    /**
     * @throws ValidationException
     */
    protected function assertParentPresence(string $childColumn, string $parentColumn): void
    {
        if (blank($this->data[$childColumn] ?? null) || filled($this->data[$parentColumn] ?? null)) {
            return;
        }

        throw ValidationException::withMessages([
            $childColumn => __('common.location_import_missing_parent', [
                'child' => __('common.'.$childColumn),
                'parent' => __('common.'.$parentColumn),
            ]),
        ]);
    }

    protected function determineTargetLevel(): string
    {
        foreach (['neighborhood', 'district', 'city', 'country'] as $column) {
            if (filled($this->data[$column] ?? null)) {
                return $column;
            }
        }

        return 'country';
    }

    protected function ensureCountry(string $name): Country
    {
        $key = $this->cacheKey($name);

        if (isset($this->countryCache[$key])) {
            return $this->countryCache[$key];
        }

        $country = Country::query()
            ->withTrashed()
            ->where('name', $name)
            ->first();

        if ($country?->trashed()) {
            $country->restore();
        }

        if (! $country) {
            try {
                $country = Country::query()->create(['name' => $name]);
            } catch (QueryException $e) {
                $country = Country::query()->where('name', $name)->first();

                if (! $country) {
                    throw $e;
                }
            }
        }

        return $this->countryCache[$key] = $country;
    }

    protected function ensureCity(Country $country, string $name): City
    {
        $key = $this->cacheKey($name, $country->id);

        if (isset($this->cityCache[$key])) {
            return $this->cityCache[$key];
        }

        $city = City::query()
            ->withTrashed()
            ->where('country_id', $country->id)
            ->where('name', $name)
            ->first();

        if ($city?->trashed()) {
            $city->restore();
        }

        if (! $city) {
            try {
                $city = City::query()->create([
                    'name' => $name,
                    'country_id' => $country->id,
                ]);
            } catch (QueryException $e) {
                $city = City::query()
                    ->where('country_id', $country->id)
                    ->where('name', $name)
                    ->first();

                if (! $city) {
                    throw $e;
                }
            }
        }

        return $this->cityCache[$key] = $city;
    }

    protected function ensureDistrict(City $city, string $name): District
    {
        $key = $this->cacheKey($name, $city->id);

        if (isset($this->districtCache[$key])) {
            return $this->districtCache[$key];
        }

        $district = District::query()
            ->withTrashed()
            ->where('city_id', $city->id)
            ->where('name', $name)
            ->first();

        if ($district?->trashed()) {
            $district->restore();
        }

        if (! $district) {
            try {
                $district = District::query()->create([
                    'name' => $name,
                    'city_id' => $city->id,
                ]);
            } catch (QueryException $e) {
                $district = District::query()
                    ->where('city_id', $city->id)
                    ->where('name', $name)
                    ->first();

                if (! $district) {
                    throw $e;
                }
            }
        }

        return $this->districtCache[$key] = $district;
    }

    protected function ensureNeighborhood(District $district, string $name): Neighborhood
    {
        $key = $this->cacheKey($name, $district->id);

        if (isset($this->neighborhoodCache[$key])) {
            return $this->neighborhoodCache[$key];
        }

        $neighborhood = Neighborhood::query()
            ->withTrashed()
            ->where('district_id', $district->id)
            ->where('name', $name)
            ->first();

        if ($neighborhood?->trashed()) {
            $neighborhood->restore();
        }

        if (! $neighborhood) {
            try {
                $neighborhood = Neighborhood::query()->create([
                    'name' => $name,
                    'district_id' => $district->id,
                ]);
            } catch (QueryException $e) {
                $neighborhood = Neighborhood::query()
                    ->where('district_id', $district->id)
                    ->where('name', $name)
                    ->first();

                if (! $neighborhood) {
                    throw $e;
                }
            }
        }

        return $this->neighborhoodCache[$key] = $neighborhood;
    }

    protected function normalizeValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    protected function allFieldsBlank(): bool
    {
        return blank($this->data['country'])
            && blank($this->data['city'])
            && blank($this->data['district'])
            && blank($this->data['neighborhood']);
    }

    protected function cacheKey(string $name, ?int $parentId = null): string
    {
        $normalized = $this->lower($name);

        return $parentId ? "{$parentId}:{$normalized}" : $normalized;
    }

    protected function lower(string $value): string
    {
        return (string) Str::of($value)->lower();
    }

    protected function resetRowContext(): void
    {
        $this->country = null;
        $this->city = null;
        $this->district = null;
        $this->neighborhood = null;
        $this->targetLevel = null;
        $this->rowPrepared = false;
    }

    public static function getHeading(): string
    {
        return __('common.import_locations');
    }

    public static function getDescription(): ?string
    {
        return __('common.location_import_description');
    }

    public static function getExampleRows(): array
    {
        return [
            [
                'country' => 'Türkiye',
            ],
            [
                'country' => 'Türkiye',
                'city' => 'İstanbul',
            ],
            [
                'country' => 'Türkiye',
                'city' => 'İstanbul',
                'district' => 'Sultangazi',
                'neighborhood' => '50. Yıl Mahallesi',
            ],
        ];
    }
}
