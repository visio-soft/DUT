<?php

namespace App\Filament\Imports;

use App\Models\Location;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LocationImporter extends Importer
{
    protected static ?string $model = Location::class;

    /**
     * @var array<string, ?string>
     */
    protected array $hierarchyNames = [];

    protected ?string $targetType = null;

    protected ?string $targetName = null;

    protected bool $hierarchyPrepared = false;

    /**
     * @return array<ImportColumn>
     */
    public static function getColumns(): array
    {
        return [
            ImportColumn::make('country')
                ->label(__('common.location_import_column_country'))
                ->example('türkiye')
                ->rules(['nullable', 'string', 'max:255']),
            ImportColumn::make('city')
                ->label(__('common.location_import_column_city'))
                ->example('istanbul')
                ->rules(['nullable', 'string', 'max:255']),
            ImportColumn::make('district')
                ->label(__('common.location_import_column_district'))
                ->example('sultnagazi')
                ->rules(['nullable', 'string', 'max:255']),
            ImportColumn::make('neighborhood')
                ->label(__('common.location_import_column_neighborhood'))
                ->example('50.mahallesi')
                ->rules(['nullable', 'string', 'max:255']),
        ];
    }

    public function resolveRecord(): ?Location
    {
        $this->resetHierarchy();
        $this->prepareHierarchy();

        $parent = $this->ensureParents();
        $slug = Str::slug($this->targetName);

        // Parent ile birlikte slug kontrolü yap
        $query = Location::where('slug', $slug)->where('type', $this->targetType);

        if ($parent) {
            $query->where('parent_id', $parent->id);
        } else {
            $query->whereNull('parent_id');
        }

        return $query->first() ?? new Location(['slug' => $slug]);
    }

    /**
     * @throws ValidationException
     */
    public function fillRecord(): void
    {
        // prepareHierarchy zaten resolveRecord'da çağrıldı
        if (!$this->hierarchyPrepared) {
            $this->resetHierarchy();
            $this->prepareHierarchy();
        }

        $parent = $this->ensureParents();

        $this->record->name = $this->targetName;
        $this->record->type = $this->targetType;
        $this->record->slug = Str::slug($this->targetName);
        $this->record->parent()->associate($parent);
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

    protected function getRequiredParentType(string $type): ?string
    {
        return match ($type) {
            Location::TYPE_CITY => Location::TYPE_COUNTRY,
            Location::TYPE_DISTRICT => Location::TYPE_CITY,
            Location::TYPE_NEIGHBORHOOD => Location::TYPE_DISTRICT,
            default => null,
        };
    }

    /**
     * Her satır için hierarchy bilgilerini sıfırla
     */
    protected function resetHierarchy(): void
    {
        $this->hierarchyNames = [];
        $this->targetType = null;
        $this->targetName = null;
        $this->hierarchyPrepared = false;
    }

    /**
     * @throws ValidationException
     */
    protected function prepareHierarchy(): void
    {
        if ($this->hierarchyPrepared) {
            return;
        }

        foreach ($this->getHierarchyOrder() as $type => $column) {
            $value = $this->normalizeValue($this->data[$column] ?? null);
            $this->hierarchyNames[$type] = $value;

            if (filled($value)) {
                $this->targetType = $type;
                $this->targetName = $value;
            }
        }

        if (! $this->targetType || ! $this->targetName) {
            throw ValidationException::withMessages([
                'country' => __('common.location_import_row_empty'),
            ]);
        }

        $this->validateHierarchy();
        $this->hierarchyPrepared = true;
    }

    /**
     * @throws ValidationException
     */
    protected function validateHierarchy(): void
    {
        foreach ($this->getHierarchyOrder() as $type => $column) {
            $value = $this->hierarchyNames[$type];

            if (blank($value)) {
                continue;
            }

            $parentType = $this->getRequiredParentType($type);

            if ($parentType && blank($this->hierarchyNames[$parentType])) {
                throw ValidationException::withMessages([
                    $column => __('common.location_import_missing_parent', [
                        'child' => __('common.' . $type),
                        'parent' => __('common.' . $parentType),
                    ]),
                ]);
            }
        }
    }

    protected function ensureParents(): ?Location
    {
        $parent = null;

        foreach ($this->getHierarchyOrder() as $type => $column) {
            if ($type === $this->targetType) {
                break;
            }

            $name = $this->hierarchyNames[$type];

            if (blank($name)) {
                continue;
            }

            $parent = $this->findOrCreateLocation($type, $name, $parent);
        }

        return $parent;
    }

    /**
     * @throws ValidationException
     */
    protected function findOrCreateLocation(string $type, string $name, ?Location $parent): Location
    {
        $slug = Str::slug($name);

        // Parent ile birlikte lokasyonu bul
        $query = Location::where('slug', $slug)->where('type', $type);

        if ($parent) {
            $query->where('parent_id', $parent->id);
        } else {
            $query->whereNull('parent_id');
        }

        $location = $query->first();

        if ($location) {
            return $location;
        }

        // Yeni lokasyon oluştur
        $location = new Location();
        $location->name = $name;
        $location->slug = $slug;
        $location->type = $type;
        $location->parent()->associate($parent);
        $location->save();

        return $location;
    }

    protected function normalizeValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    /**
     * @return array<string, string>
     */
    protected function getHierarchyOrder(): array
    {
        return [
            Location::TYPE_COUNTRY => 'country',
            Location::TYPE_CITY => 'city',
            Location::TYPE_DISTRICT => 'district',
            Location::TYPE_NEIGHBORHOOD => 'neighborhood',
        ];
    }

    protected function getColumnNameForType(string $type): string
    {
        return $this->getHierarchyOrder()[$type] ?? 'country';
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
                'country' => 'türkiye',
            ],
            [
                'country' => 'türkiye',
                'city' => 'istanbul',
            ],
            [
                'country' => 'türkiye',
                'city' => 'istanbul',
                'district' => 'sultnagazi',
                'neighborhood' => '50.mahallesi',
            ],
        ];
    }
}
