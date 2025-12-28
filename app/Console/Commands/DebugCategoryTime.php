<?php

namespace App\Console\Commands;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DebugCategoryTime extends Command
{
    protected $signature = 'debug:category-time';

    protected $description = 'Debug category time calculations';

    public function handle()
    {
        $now = Carbon::now('Europe/Istanbul');
        $this->info('Şu anki zaman: '.$now->format('Y-m-d H:i:s'));

        $categories = Category::whereNotNull('end_datetime')->get();

        foreach ($categories as $cat) {
            $this->info("\n--- Kategori: {$cat->name} (ID: {$cat->id}) ---");
            $this->info('Başlangıç: '.$cat->start_datetime);
            $this->info('Bitiş: '.$cat->end_datetime);

            $end = Carbon::parse($cat->end_datetime, 'Europe/Istanbul');
            $diff = $end->diffInMinutes($now, false);
            $isExpired = $now->greaterThan($end);

            $this->info("Kalan dakika: {$diff}");
            $this->info('Süre doldu mu: '.($isExpired ? 'EVET' : 'HAYIR'));

            if (! $isExpired) {
                $this->info('JavaScript formatı: '.$this->formatCountdown($diff * 60 * 1000));
            }
        }
    }

    private function formatCountdown($diffMs)
    {
        if ($diffMs <= 0) {
            return 'Süre doldu';
        }

        $totalSeconds = floor($diffMs / 1000);
        $totalMinutes = floor($totalSeconds / 60);
        $totalHours = floor($totalMinutes / 60);
        $totalDays = floor($totalHours / 24);

        $seconds = $totalSeconds % 60;
        $minutes = $totalMinutes % 60;
        $hours = $totalHours % 24;

        if ($totalDays >= 7) {
            $weeks = floor($totalDays / 7);
            $remainingDays = $totalDays % 7;
            if ($remainingDays > 0) {
                return "{$weeks} hafta {$remainingDays} gün";
            } else {
                return "{$weeks} hafta";
            }
        }

        if ($totalDays >= 1) {
            if ($hours > 0) {
                return "{$totalDays} gün {$hours} saat";
            } else {
                return "{$totalDays} gün";
            }
        }

        if ($totalHours >= 1) {
            if ($minutes > 0) {
                return "{$totalHours} saat {$minutes} dk";
            } else {
                return "{$totalHours} saat";
            }
        }

        if ($totalMinutes >= 1) {
            if ($seconds > 0) {
                return "{$totalMinutes} dk {$seconds} sn";
            } else {
                return "{$totalMinutes} dk";
            }
        }

        return "{$totalSeconds} sn";
    }
}
