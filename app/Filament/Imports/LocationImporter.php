<?php

namespace App\Filament\Imports;

use App\Models\Location;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LocationImporter extends Importer
{
    protected static ?string $model = Location::class;

    protected ?Location $country = null;
    protected ?Location $city = null;
    protected ?Location $district = null;
    protected ?Location $neighborhood = null;

    protected ?string $targetLevel = null;
    protected bool $rowPrepared = false;

    // Caches using slug or unique key combination
    protected array $countryCache = [];
    protected array $cityCache = [];
    protected array $districtCache = [];
    protected array $neighborhoodCache = [];

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

        // 1. Ensure Country
        $this->country = $this->ensureLocation(
            name: $this->data['country'],
            type: Location::TYPE_COUNTRY,
            parent: null
        );

        // 2. Ensure City
        if (filled($this->data['city'])) {
            $this->city = $this->ensureLocation(
                name: $this->data['city'],
                type: Location::TYPE_CITY,
                parent: $this->country
            );
        }

        // 3. Ensure District
        if (filled($this->data['district'])) {
            $this->district = $this->ensureLocation(
                name: $this->data['district'],
                type: Location::TYPE_DISTRICT,
                parent: $this->city
            );
        }

        // Return based on target level
        if ($this->targetLevel === 'country') {
            return $this->country;
        }

        if ($this->targetLevel === 'city') {
            return $this->city;
        }

        if ($this->targetLevel === 'district') {
            return $this->district;
        }

        // 4. Ensure Neighborhood
        $this->neighborhood = $this->ensureLocation(
            name: $this->data['neighborhood'],
            type: Location::TYPE_NEIGHBORHOOD,
            parent: $this->district
        );

        return $this->neighborhood;
    }

    public function fillRecord(): void
    {
        // Records are created/retrieved in resolveRecord.
        // We might want to update fields if they changed, strictly speaking Importer handles creation well.
        // But here we are resolving structural hierarchy.
        // If we found an existing record, we might want to update its name if it differs slightly?
        // For now, let's assume if it exists we leave it be, as resolveRecord ensures existence.
        
        $record = $this->getRecord();
        
        // Update name if needed
        if ($record && $record->name !== $this->data[$this->targetLevel]) {
             $record->name = $this->data[$this->targetLevel];
        }
    }

    protected function ensureLocation(string $name, string $type, ?Location $parent = null): Location
    {
        // Simple caching key
        $parentId = $parent?->id;
        $key = "{$type}_{$parentId}_" . Str::slug($name);

        $cacheProp = $type . 'Cache';
        
        // Dynamically access cache property if possible or use generic array
        // Optimized: just use one main cache array keyed by type too
        
        if (isset($this->{$cacheProp}[$key])) {
            return $this->{$cacheProp}[$key];
        }

        // Look up existing
        $query = Location::query()
            ->where('type', $type)
            ->where('name', $name);

        if ($parent) {
            $query->where('parent_id', $parent->id);
        } else {
            $query->whereNull('parent_id');
        }

        $location = $query->first();

        if (!$location) {
             try {
                $location = Location::create([
                    'type' => $type,
                    'name' => $name,
                    'parent_id' => $parent?->id,
                    // slug is handled by model boot
                ]);
            } catch (QueryException $e) {
                // Concurrency fallback
                $location = $query->first();
                if (!$location) throw $e;
            }
        }

        // Cache it
        if (property_exists($this, $cacheProp)) {
            $this->{$cacheProp}[$key] = $location;
        }

        return $location;
    }

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

    protected function validateHierarchy(): void
    {
        $this->assertParentPresence('city', 'country');
        $this->assertParentPresence('district', 'city');
        $this->assertParentPresence('neighborhood', 'district');
    }

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

    protected function resetRowContext(): void
    {
        $this->country = null;
        $this->city = null;
        $this->district = null;
        $this->neighborhood = null;
        $this->targetLevel = null;
        $this->rowPrepared = false;
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
}
