<?php

// Boot Laravel to access DB facade
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$keep = ['categories', 'migrations'];
$tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
$names = array_map(fn ($t) => $t->tablename, $tables);
$toTruncate = array_filter($names, fn ($n) => ! in_array($n, $keep));

if (count($toTruncate) === 0) {
    echo "Nothing to truncate\n";
    exit(0);
}

$quoted = array_map(fn ($t) => '"'.$t.'"', $toTruncate);
$sql = 'TRUNCATE '.implode(', ', $quoted).' RESTART IDENTITY CASCADE';

try {
    DB::beginTransaction();
    DB::statement($sql);
    DB::commit();
    echo 'Truncated: '.implode(', ', $toTruncate).PHP_EOL;
} catch (Throwable $e) {
    DB::rollBack();
    echo 'Error: '.$e->getMessage().PHP_EOL;
    exit(1);
}

// Print row counts after truncate
foreach ($names as $name) {
    try {
        $count = DB::table($name)->count();
    } catch (Throwable $e) {
        $count = 'ERR';
    }
    echo $name.': '.$count.PHP_EOL;
}
