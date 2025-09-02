<x-filament-panels::page>
    <div class="space-y-6">
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-header flex flex-col gap-3 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="grid place-items-center rounded-2xl bg-gray-50 p-2 dark:bg-gray-800">
                        <x-heroicon-o-paint-brush class="h-5 w-5 text-gray-500 dark:text-gray-400" />
                    </div>
                    <div class="grid flex-1 gap-1">
                        <h3 class="text-base font-semibold leading-6 text-gray-950 dark:text-white">
                            Tasarım Verileri
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Projelerin tasarım verilerini görüntüleyin, düzenleyin ve indirin.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-content p-6">
                {{ $this->table }}
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="fi-stats-card rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center gap-3">
                    <div class="grid place-items-center rounded-2xl bg-blue-50 p-2 dark:bg-blue-950/20">
                        <x-heroicon-o-cube class="h-5 w-5 text-blue-500" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">
                            Toplam Tasarım
                        </p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ \App\Models\Project::whereNotNull('design_landscape')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="fi-stats-card rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center gap-3">
                    <div class="grid place-items-center rounded-2xl bg-green-50 p-2 dark:bg-green-950/20">
                        <x-heroicon-o-check-circle class="h-5 w-5 text-green-500" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">
                            Tamamlanan
                        </p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ \App\Models\Project::where('design_completed', true)->whereNotNull('design_landscape')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="fi-stats-card rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center gap-3">
                    <div class="grid place-items-center rounded-2xl bg-yellow-50 p-2 dark:bg-yellow-950/20">
                        <x-heroicon-o-clock class="h-5 w-5 text-yellow-500" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">
                            Devam Eden
                        </p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ \App\Models\Project::where('design_completed', false)->whereNotNull('design_landscape')->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
