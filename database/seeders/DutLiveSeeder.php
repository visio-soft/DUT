<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class DutLiveSeeder extends Seeder
{
    /**
     * Import the live dump from postgres.sql into the database
     * and migrate legacy tables to new structure.
     *
     * Works with both PostgreSQL and SQLite databases.
     */
    public function run(): void
    {
        $sqlPath = base_path('postgres.sql');

        if (! File::exists($sqlPath)) {
            $this->command?->error("postgres.sql not found at {$sqlPath}");
            return;
        }

        $sql = File::get($sqlPath);

        if (trim($sql) === '') {
            $this->command?->warn('postgres.sql is empty, nothing to import.');
            return;
        }

        $connection = config('database.default');

        if ($connection === 'pgsql') {
            // For PostgreSQL, use the raw import
            $this->importPostgresql($sql);
        } else {
            // For SQLite or other databases, parse and import
            $this->importParsed($sql);
        }

        $this->command?->info('Legacy data import and migration completed successfully.');
    }

    /**
     * Import PostgreSQL dump directly.
     */
    private function importPostgresql(string $sql): void
    {
        $constraintsDisabled = false;

        try {
            DB::statement('SET session_replication_role = replica');
            $constraintsDisabled = true;
        } catch (\Throwable $e) {
            $this->command?->warn('Could not disable constraints; importing dump as-is: ' . $e->getMessage());
        }

        try {
            DB::unprepared($sql);
            $this->command?->info('Imported postgres.sql (dut live dump).');
        } catch (\Throwable $e) {
            $this->command?->error('Import failed: ' . $e->getMessage());
            throw $e;
        } finally {
            if ($constraintsDisabled) {
                DB::statement('SET session_replication_role = DEFAULT');
            }
        }
    }

    /**
     * Parse PostgreSQL dump and import to any database.
     */
    private function importParsed(string $sql): void
    {
        $this->command?->info('Parsing postgres.sql for cross-database import...');

        // Disable foreign key checks for SQLite
        if (config('database.default') === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        }

        try {
            // Parse INSERT statements from the SQL dump
            $this->parseAndImportData($sql);

            // Create project groups and link data
            $this->createDefaultProjectGroups();

        } finally {
            if (config('database.default') === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = ON');
            }
        }
    }

    /**
     * Parse INSERT statements from PostgreSQL dump and execute them.
     */
    private function parseAndImportData(string $sql): void
    {
        // Table mapping: legacy -> new
        $tableMapping = [
            'oneriler' => 'suggestions',
            'oneri_likes' => 'suggestion_likes',
            'oneri_comments' => 'suggestion_comments',
            'oneri_comment_likes' => 'suggestion_comment_likes',
        ];

        // Column mapping: legacy -> new
        $columnMapping = [
            'oneri_id' => 'suggestion_id',
            'oneri_comment_id' => 'suggestion_comment_id',
            'street_cadde' => 'street_avenue',
            'street_sokak' => 'street_road',
            'budget' => 'min_budget', // We'll duplicate to max_budget
        ];

        // Parse INSERT statements
        preg_match_all('/INSERT INTO "public"\."([^"]+)" \(([^)]+)\) VALUES\s*([\s\S]*?);(?=\s*(?:INSERT|CREATE|ALTER|--|$))/mi', $sql, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $tableName = $match[1];
            $columns = $match[2];
            $valuesBlock = $match[3];

            // Map table name
            $targetTable = $tableMapping[$tableName] ?? $tableName;

            // Skip if table doesn't exist
            if (!Schema::hasTable($targetTable)) {
                $this->command?->warn("Table {$targetTable} does not exist, skipping...");
                continue;
            }

            // Parse columns
            $columnList = array_map(function($col) {
                return trim(str_replace('"', '', $col));
            }, explode(',', $columns));

            // Map columns
            $mappedColumns = array_map(function($col) use ($columnMapping) {
                return $columnMapping[$col] ?? $col;
            }, $columnList);

            // Add extra columns for suggestions table
            if ($targetTable === 'suggestions' && in_array('budget', $columnList)) {
                // Find budget column index and add max_budget after it
                $budgetIndex = array_search('min_budget', $mappedColumns);
                if ($budgetIndex !== false) {
                    // max_budget will be inserted after min_budget
                    array_splice($mappedColumns, $budgetIndex + 1, 0, 'max_budget');
                }
            }

            // Parse all value rows
            $this->parseAndInsertValues($targetTable, $columnList, $mappedColumns, $valuesBlock, $tableName);
        }
    }

    /**
     * Parse value rows and insert them.
     */
    private function parseAndInsertValues(string $targetTable, array $originalColumns, array $mappedColumns, string $valuesBlock, string $originalTable): void
    {
        $valuesBlock = trim($valuesBlock);

        // Better regex to match rows - handles newlines inside values
        // Split on ),\n( pattern more accurately
        $valuesBlock = preg_replace('/\)\s*,\s*\n\s*\(/', ")###ROWSEP###(", $valuesBlock);
        $rows = explode("###ROWSEP###", $valuesBlock);

        $insertedCount = 0;

        foreach ($rows as $row) {
            // Remove outer parentheses
            $row = trim($row);
            $row = preg_replace('/^\(/', '', $row);
            $row = preg_replace('/\)$/', '', $row);

            $values = $this->parseRowValues($row);

            if (count($values) !== count($originalColumns)) {
                $this->command?->warn("Column count mismatch for {$targetTable}: expected " . count($originalColumns) . ", got " . count($values));
                continue;
            }

            $data = [];
            $hasBudget = false;
            $budgetValue = null;

            for ($i = 0; $i < count($originalColumns); $i++) {
                $col = $originalColumns[$i];
                $mappedCol = $mappedColumns[$i] ?? $col;
                $value = $values[$i];

                // Handle NULL values
                if (strtoupper(trim($value)) === 'NULL') {
                    $value = null;
                } else {
                    // Remove quotes
                    $value = trim($value, "'");
                    // Handle PostgreSQL escape sequences
                    $value = str_replace("''", "'", $value);
                }

                // Handle boolean values from PostgreSQL
                if ($value === 'TRUE' || $value === 'true') {
                    $value = 1;
                } elseif ($value === 'FALSE' || $value === 'false') {
                    $value = 0;
                }

                // Track budget for duplication
                if ($col === 'budget') {
                    $hasBudget = true;
                    $budgetValue = $value;
                }

                $data[$mappedCol] = $value;
            }

            // Duplicate budget to max_budget for suggestions
            if ($targetTable === 'suggestions' && $hasBudget) {
                $data['max_budget'] = $budgetValue;
            }

            // Add status for suggestions if not present
            if ($targetTable === 'suggestions' && !isset($data['status'])) {
                $data['status'] = 'active';
            }

            // Set default city if empty
            if ($targetTable === 'suggestions' && (empty($data['city']) || $data['city'] === null)) {
                $data['city'] = 'İstanbul';
            }

            // Filter out columns that don't exist in the target table
            $tableColumns = Schema::getColumnListing($targetTable);
            $data = array_filter($data, function($key) use ($tableColumns) {
                return in_array($key, $tableColumns);
            }, ARRAY_FILTER_USE_KEY);

            try {
                DB::table($targetTable)->insert($data);
                $insertedCount++;
            } catch (\Throwable $e) {
                // Skip duplicates or other errors
                if (strpos($e->getMessage(), 'UNIQUE') === false && strpos($e->getMessage(), 'duplicate') === false) {
                    $this->command?->warn("Error inserting into {$targetTable}: " . $e->getMessage());
                }
            }
        }

        if ($insertedCount > 0) {
            $this->command?->info("Inserted {$insertedCount} records into {$targetTable}" . ($originalTable !== $targetTable ? " (from {$originalTable})" : ""));
        }
    }

    /**
     * Parse a single row's values, handling quoted strings properly.
     */
    private function parseRowValues(string $row): array
    {
        $values = [];
        $current = '';
        $inQuote = false;
        $depth = 0;

        for ($i = 0; $i < strlen($row); $i++) {
            $char = $row[$i];

            if ($char === "'" && ($i === 0 || $row[$i-1] !== "'")) {
                $inQuote = !$inQuote;
                $current .= $char;
            } elseif ($char === '(' && !$inQuote) {
                $depth++;
                $current .= $char;
            } elseif ($char === ')' && !$inQuote) {
                $depth--;
                $current .= $char;
            } elseif ($char === ',' && !$inQuote && $depth === 0) {
                $values[] = trim($current);
                $current = '';
            } else {
                $current .= $char;
            }
        }

        if (trim($current) !== '') {
            $values[] = trim($current);
        }

        return $values;
    }

    /**
     * Create proper hierarchy from legacy data:
     *
     * Legacy Structure:
     * - categories (id=2: "Kayabaşı Meydan Projesi") -> This should become a PROJECT
     * - oneriler (linked to category_id=2) -> These are the SUGGESTIONS
     *
     * New Structure:
     * - categories: "Projeler" (main category)
     *   - project_groups: "Kayabaşı Meydan Projesi"
     *     - projects (suggestions with project_id=NULL): "Kayabaşı Meydan Projesi"
     *       - suggestions (with project_id set): "Eco-Meydan", "Çocuk Konseptli...", etc.
     */
    private function createDefaultProjectGroups(): void
    {
        $this->command?->info('Restructuring legacy data to proper hierarchy...');

        // Step 1: Create a main "Projeler" category if it doesn't exist
        $mainCategory = DB::table('categories')->where('name', 'Projeler')->first();
        if (!$mainCategory) {
            $mainCategoryId = DB::table('categories')->insertGetId([
                'name' => 'Projeler',
                'description' => 'Tüm projeler',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command?->info("Created main 'Projeler' category.");
        } else {
            $mainCategoryId = $mainCategory->id;
        }

        // Step 2: Get all legacy categories (which are actually projects)
        $legacyCategories = DB::table('categories')
            ->where('name', '!=', 'Projeler')
            ->get();

        foreach ($legacyCategories as $legacyCategory) {
            // Step 3: Create a project group for this legacy category
            $projectGroup = DB::table('project_groups')
                ->where('name', $legacyCategory->name)
                ->where('category_id', $mainCategoryId)
                ->first();

            if (!$projectGroup) {
                $projectGroupId = DB::table('project_groups')->insertGetId([
                    'name' => $legacyCategory->name,
                    'category_id' => $mainCategoryId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->command?->info("Created project group: '{$legacyCategory->name}'");
            } else {
                $projectGroupId = $projectGroup->id;
            }

            // Step 4: Create a PROJECT from the legacy category data
            // Check if project already exists
            $existingProject = DB::table('suggestions')
                ->where('title', $legacyCategory->name)
                ->whereNull('project_id')
                ->first();

            if (!$existingProject) {
                $projectId = DB::table('suggestions')->insertGetId([
                    'category_id' => $mainCategoryId,
                    'project_id' => null, // NULL means this is a PROJECT, not a suggestion
                    'title' => $legacyCategory->name,
                    'description' => $legacyCategory->description,
                    'status' => 'active',
                    'city' => $legacyCategory->province ?? 'İstanbul',
                    'district' => $legacyCategory->district,
                    'neighborhood' => $legacyCategory->neighborhood,
                    'address' => $legacyCategory->detailed_address,
                    'created_at' => $legacyCategory->created_at ?? now(),
                    'updated_at' => $legacyCategory->updated_at ?? now(),
                ]);
                $this->command?->info("Created project: '{$legacyCategory->name}' (ID: {$projectId})");
            } else {
                $projectId = $existingProject->id;
                $this->command?->info("Project already exists: '{$legacyCategory->name}' (ID: {$projectId})");
            }

            // Step 5: Link project to project group
            $linkExists = DB::table('project_group_suggestion')
                ->where('project_group_id', $projectGroupId)
                ->where('suggestion_id', $projectId)
                ->exists();

            if (!$linkExists) {
                DB::table('project_group_suggestion')->insert([
                    'project_group_id' => $projectGroupId,
                    'suggestion_id' => $projectId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Step 6: Update all suggestions that were linked to this legacy category
            // They should now point to the new project
            $suggestions = DB::table('suggestions')
                ->where('category_id', $legacyCategory->id)
                ->whereNull('project_id')
                ->where('id', '!=', $projectId) // Don't update the project itself
                ->get();

            foreach ($suggestions as $suggestion) {
                DB::table('suggestions')
                    ->where('id', $suggestion->id)
                    ->update([
                        'project_id' => $projectId,
                        'category_id' => $mainCategoryId, // Update category to main
                    ]);
                $this->command?->info("  - Linked suggestion '{$suggestion->title}' to project (ID: {$projectId})");
            }

            // Step 7: Move media from legacy category to new project
            DB::table('media')
                ->where('model_type', 'App\\Models\\Category')
                ->where('model_id', $legacyCategory->id)
                ->update([
                    'model_type' => 'App\\Models\\Project',
                    'model_id' => $projectId,
                ]);
        }

        $this->command?->info('Legacy data restructuring completed.');
    }
}
