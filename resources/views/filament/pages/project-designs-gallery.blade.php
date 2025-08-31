<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Projects grouped by category -->
        @forelse($this->projectDesigns as $group)
            <div class="category-section">
                <h2 class="text-xl font-semibold mb-4">{{ $group['category_name'] }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    @foreach($group['designs'] as $design)
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

                                    <!-- Like toggle: only one per category allowed (backend enforces) -->
                                    <div class="inline-flex items-center" style="margin-left: 0.8rem;">
                                        <button
                                            type="button"
                                            wire:click="toggleLike({{ $design['id'] }})"
                                            class="{{ $design['is_liked'] ? 'like-button liked' : 'like-button' }} inline-flex items-center justify-center p-0 rounded-full transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-primary-500"
                                            title="Beğen / Beğeniyi Kaldır"
                                            aria-pressed="{{ $design['is_liked'] ? 'true' : 'false' }}"
                                        >
                                            <!-- BEĞENİ İKONU: START (kalp ikonları: outline / solid) -->
                                            @if($design['is_liked'])
                                                <x-heroicon-s-heart class="w-14 h-14" />
                                            @else
                                                <x-heroicon-o-heart class="w-14 h-14 " />
                                            @endif
                                            <!-- BEĞENİ İKONU: END -->
                                        </button>

                                        <span class="text-sm ml-2">{{ $design['likes_count'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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

        /* Like button styles */
        .like-button {
            background: transparent;
            border: 1px solid transparent;
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .like-button:hover {
            background: rgba(59,130,246,0.06); /* primary-600 6% */
            transform: translateY(-1px);
        }

        .like-button.liked {
            background: rgba(0, 0, 0, 0); /* subtle danger bg */
            border-color: rgba(239, 68, 68, 0);
        }

        /* Ensure svg icons inside button scale nicely */
        .like-button svg {
            width: 25px;
            height: 25px;
        }
    </style>
</x-filament-panels::page>
