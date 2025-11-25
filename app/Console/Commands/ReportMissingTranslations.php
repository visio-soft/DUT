<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Throwable;

class ReportMissingTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:report 
        {group=common : Translation group / file name to inspect} 
        {--paths=* : Relative paths to scan (defaults to app, resources, routes)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan the codebase for translation usages and report missing keys per locale.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $group = (string) $this->argument('group');
        $paths = array_filter($this->option('paths')) ?: ['app', 'resources', 'routes'];

        $usedKeys = $this->collectUsedKeys($group, $paths);

        if (empty($usedKeys)) {
            $this->info("No '{$group}' translation keys were found in the provided paths.");

            return self::SUCCESS;
        }

        $this->info(sprintf("Detected %d unique '%s' keys inside the codebase.", count($usedKeys), $group));

        $locales = $this->discoverLocales();

        if (empty($locales)) {
            $this->warn('No locale directories were found under '.lang_path().'.');

            return self::SUCCESS;
        }

        foreach ($locales as $locale) {
            $missing = $this->missingKeysForLocale($locale, $group, $usedKeys);

            if (empty($missing)) {
                $this->line("[$locale] All {$group} translations are present.");

                continue;
            }

            $this->warn(sprintf('[%s] Missing %d keys:', $locale, count($missing)));

            foreach ($missing as $key) {
                $this->line("  - {$group}.{$key}");
            }
        }

        return self::SUCCESS;
    }

    /**
     * Collect the translation keys that are referenced in the given paths.
     *
     * @return array<int, string>
     */
    protected function collectUsedKeys(string $group, array $paths): array
    {
        $pattern = "/(?:__|@lang|trans|trans_choice)\\(\\s*['\"]"
            .preg_quote($group, '/')
            ."\\.([\\w\\-]+)['\"]/u";

        $found = [];

        foreach ($paths as $relativePath) {
            $fullPath = base_path($relativePath);

            if (! File::exists($fullPath)) {
                continue;
            }

            foreach (File::allFiles($fullPath) as $file) {
                $pathName = $file->getPathname();

                if (! Str::endsWith($pathName, ['.php', '.blade.php'])) {
                    continue;
                }

                try {
                    $contents = File::get($pathName);
                } catch (Throwable $exception) {
                    $this->warn("Unable to read {$pathName}: {$exception->getMessage()}");

                    continue;
                }

                if (preg_match_all($pattern, $contents, $matches)) {
                    foreach ($matches[1] as $key) {
                        $found[$key] = true;
                    }
                }
            }
        }

        $keys = array_keys($found);
        sort($keys);

        return $keys;
    }

    /**
     * Discover available locales from the lang directory.
     *
     * @return array<int, string>
     */
    protected function discoverLocales(): array
    {
        if (! File::isDirectory(lang_path())) {
            return [];
        }

        return collect(File::directories(lang_path()))
            ->map(fn ($directory) => basename($directory))
            ->reject(fn ($name) => $name === 'vendor')
            ->sort()
            ->values()
            ->all();
    }

    /**
     * Determine which keys are missing for a locale.
     *
     * @param  array<int, string>  $keys
     * @return array<int, string>
     */
    protected function missingKeysForLocale(string $locale, string $group, array $keys): array
    {
        $filePath = lang_path("{$locale}/{$group}.php");

        if (! File::exists($filePath)) {
            $this->warn("Language file missing for locale '{$locale}': {$group}.php");

            return $keys;
        }

        $translations = include $filePath;

        if (! is_array($translations)) {
            $this->warn("Language file {$filePath} does not return an array.");

            return $keys;
        }

        $definedKeys = array_keys($translations);

        return array_values(array_diff($keys, $definedKeys));
    }
}
