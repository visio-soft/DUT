<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Projects Grid -->
        <div id="projectsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($this->projectDesigns as $design)
                <div class="project-card bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 overflow-hidden transition-all duration-300 hover:shadow-lg hover:scale-105"
                     data-likes="{{ $design['likes_count'] }}"
                     data-budget="{{ $design['project_budget'] }}"
                     data-name="{{ $design['project_name'] }}"
                     data-created="{{ $design['created_at'] }}">

                    <!-- Project Image -->
                    <div class="relative overflow-hidden">
                        @if($design['project_image'])
                            <img src="{{ $design['project_image'] }}"
                                 alt="{{ $design['project_name'] }}"
                                 class="w-full h-48 object-cover transition-transform duration-300 hover:scale-110">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                                <x-heroicon-o-photo class="w-12 h-12 text-white" />
                            </div>
                        @endif

                        <!-- Popular Badge -->
                        @if($design['likes_count'] >= 3)
                            <div class="absolute top-3 left-3 bg-primary-500 text-white px-2 py-1 rounded-full text-xs font-semibold flex items-center space-x-1">
                                <x-heroicon-s-star class="w-3 h-3" />
                                <span>Popüler</span>
                            </div>
                        @endif
                    </div>

                    <!-- Project Info -->
                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-gray-900 dark:text-white mb-2 truncate" title="{{ $design['project_name'] }}">
                            {{ $design['project_name'] }}
                        </h3>

                        <div class="flex items-center justify-between mb-3" style="padding-bottom: 1rem;">
                            <span class="text-success-600 dark:text-success-400 font-bold text-lg">
                                ₺{{ number_format($design['project_budget'], 0) }}
                            </span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            <!-- View Design Button -->
                            <a href="{{ url('/admin/project-designs/' . $design['id']) }}"
                               class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition duration-75">
                                <x-heroicon-o-eye class="w-4 h-4" />
                                Tasarımı Görüntüle
                            </a>

                            <!-- Like Button -->
                            <button wire:click="toggleLike({{ $design['id'] }})"
                                    style="margin-left: 0.8rem;" class="inline-flex items-center justify-center gap-1 rounded-lg px-3 py-2 text-sm font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-75 {{ $design['is_liked'] ? 'bg-danger-100 text-danger-700 hover:bg-danger-200 focus:ring-danger-500' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-gray-500' }}">
                                <x-heroicon-s-heart class="w-4 h-4 {{ $design['is_liked'] ? 'text-danger-500' : 'text-gray-400' }}" />
                                <span>{{ $design['likes_count'] }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <x-filament::section>
                        <x-slot name="heading">
                            Henüz tasarım bulunmuyor
                        </x-slot>

                        <div class="text-center py-12">
                            <x-heroicon-o-paint-brush class="mx-auto w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" />
                            <p class="text-gray-500 dark:text-gray-400">İlk proje tasarımını oluşturun!</p>
                        </div>
                    </x-filament::section>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        .project-card {
            position: relative;
        }

        .project-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.6s;
            z-index: 1;
            pointer-events: none;
        }

        .project-card:hover::before {
            transform: translateX(100%);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.25s ease forwards;
        }
    </style>
</x-filament-panels::page>
