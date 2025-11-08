<?php

// Boot Laravel to access DB/Artisan
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

// Simple CLI args parsing: --keep=table1,table2 --seeders=ClassA,ClassB
$opts = [];
foreach ($argv as $arg) {
    if (str_starts_with($arg, '--keep=')) {
        $opts['keep'] = explode(',', substr($arg, strlen('--keep=')));
    }
    if (str_starts_with($arg, '--seeders=')) {
        $opts['seeders'] = explode(',', substr($arg, strlen('--seeders=')));
    }
}

$keep = $opts['keep'] ?? ['migrations', 'categories'];
$seeders = $opts['seeders'] ?? ['Database\\Seeders\\DatabaseSeeder'];

echo 'Keep tables: '.implode(', ', $keep).PHP_EOL;
echo 'Seeders: '.implode(', ', $seeders).PHP_EOL;

function getAllTableNames()
{
    $pdo = DB::getPdo();
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

    if ($driver === 'pgsql') {
        $rows = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");

        return array_map(fn ($r) => $r->tablename, $rows);
    }

    if ($driver === 'sqlite') {
        $rows = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");

        return array_map(fn ($r) => $r->name, $rows);
    }

    // default: mysql
    try {
        $rows = DB::select('SHOW TABLES');
        // SHOW TABLES returns objects with key like 'Tables_in_databasename'
        $names = [];
        foreach ($rows as $r) {
            $arr = (array) $r;
            $names[] = array_values($arr)[0];
        }

        return $names;
    } catch (Throwable $e) {
        throw $e;
    }
}

try {
    $all = getAllTableNames();
} catch (Throwable $e) {
    echo 'Failed to list tables: '.$e->getMessage().PHP_EOL;
    exit(1);
}

$toTruncate = array_filter($all, fn ($t) => ! in_array($t, $keep));

if (count($toTruncate) === 0) {
    echo "No tables to truncate.\n";
} else {
    $pdo = DB::getPdo();
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

    echo 'Truncating: '.implode(', ', $toTruncate).PHP_EOL;

    try {
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            foreach ($toTruncate as $table) {
                DB::statement('TRUNCATE `'.$table.'`');
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        } elseif ($driver === 'sqlite') {
            DB::beginTransaction();
            foreach ($toTruncate as $table) {
                DB::statement("DELETE FROM \"$table\"");
                DB::statement("DELETE FROM sqlite_sequence WHERE name='$table'");
            }
            DB::commit();
        } else { // pgsql and others
            $quoted = array_map(fn ($t) => '"'.$t.'"', $toTruncate);
            $sql = 'TRUNCATE '.implode(', ', $quoted).' RESTART IDENTITY CASCADE';
            DB::statement($sql);
        }

        echo "Truncate succeeded.\n";
    } catch (Throwable $e) {
        echo 'Truncate error: '.$e->getMessage().PHP_EOL;
        exit(1);
    }

    // Print simple counts
    foreach ($all as $name) {
        try {
            $count = DB::table($name)->count();
        } catch (Throwable $e) {
            $count = 'ERR';
        }
        echo $name.': '.$count.PHP_EOL;
    }
}

// Run seeders
foreach ($seeders as $seeder) {
    echo "Seeding: $seeder\n";
    try {
        $exit = Artisan::call('db:seed', ['--class' => $seeder, '--force' => true]);
        $output = Artisan::output();
        echo $output.PHP_EOL;
        if ($exit !== 0) {
            echo "Seeder $seeder exited with code: $exit\n";
        } else {
            echo "Seeder $seeder completed.\n";
        }
    } catch (Throwable $e) {
        echo 'Seeder error: '.$e->getMessage().PHP_EOL;
        exit(1);
    }
}

echo "Done.\n";
