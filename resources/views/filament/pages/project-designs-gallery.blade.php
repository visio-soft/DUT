<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Filter and Sort Controls -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between space-y-4 md:space-y-0 md:space-x-4">
                <div class="flex items-center space-x-2">
                    <x-heroicon-o-funnel class="w-5 h-5 text-gray-500" />
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filtrele & Sırala</h3>
                </div>
                
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                    <!-- Sort Options -->
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Sırala:</label>
                        <select class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-sm focus:ring-primary-500 focus:border-primary-500" wire:model.live="sortBy">
                            <option value="newest">En Yeni</option>
                            <option value="popular">En Popüler</option>
                            <option value="price_high">Fiyat (Yüksek-Düşük)</option>
                            <option value="price_low">Fiyat (Düşük-Yüksek)</option>
                        </select>
                    </div>

                    <!-- Price Filter -->
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Fiyat:</label>
                        <select class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-sm focus:ring-primary-500 focus:border-primary-500" wire:model.live="priceFilter">
                            <option value="">Tümü</option>
                            <option value="0-10000">₺0 - ₺10,000</option>
                            <option value="10000-25000">₺10,000 - ₺25,000</option>
                            <option value="25000-50000">₺25,000 - ₺50,000</option>
                            <option value="50000+">₺50,000+</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects grouped by category -->
        @forelse($this->projectDesigns as $index => $group)
            <div class="category-section bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 overflow-hidden">
                <!-- Category Header with Accordion Toggle -->
                <div class="category-header cursor-pointer p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200"
                     onclick="toggleCategory({{ $index }})">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-primary-100 dark:bg-primary-900 rounded-lg">
                                <x-heroicon-o-folder class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $group['category_name'] }}</h2>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                {{ count($group['designs']) }} tasarım
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <x-heroicon-o-chevron-down class="w-5 h-5 text-gray-500 transition-transform duration-200 category-chevron-{{ $index }}" />
                        </div>
                    </div>
                </div>

                <!-- Category Content -->
                <div class="category-content category-content-{{ $index }}" style="display: block;">
                    <div class="p-6 pt-0">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
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

                                <!-- Status Badges -->
                                <div class="absolute top-3 left-3 flex flex-col space-y-1">
                                    <!-- Popular Badge -->
                                    @if($design['likes_count'] >= 3)
                                        <div class="bg-amber-500 text-white px-2 py-1 rounded-full text-xs font-semibold flex items-center space-x-1 shadow-lg">
                                            <x-heroicon-s-star class="w-3 h-3" />
                                            <span>Popüler</span>
                                        </div>
                                    @endif

                                    <!-- New Badge (for designs created in last 7 days) -->
                                    @if(\Carbon\Carbon::createFromTimestamp($design['created_at'])->diffInDays() <= 7)
                                        <div class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-semibold flex items-center space-x-1 shadow-lg">
                                            <x-heroicon-s-sparkles class="w-3 h-3" />
                                            <span>Yeni</span>
                                        </div>
                                    @endif

                                    <!-- Featured Badge (for high budget designs) -->
                                    @if($design['project_budget'] >= 50000)
                                        <div class="bg-purple-500 text-white px-2 py-1 rounded-full text-xs font-semibold flex items-center space-x-1 shadow-lg">
                                            <x-heroicon-s-trophy class="w-3 h-3" />
                                            <span>Öne Çıkan</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Project Info -->
                            <div class="p-5">
                                <!-- Project Name -->
                                <div class="mb-4">
                                    <h3 class="font-semibold text-lg text-gray-900 dark:text-white line-clamp-2 min-h-[3.5rem]" title="{{ $design['project_name'] }}">
                                        {{ $design['project_name'] }}
                                    </h3>
                                </div>

                                <!-- Price Section -->
                                <div class="mb-4 p-3 bg-gradient-to-r from-success-50 to-success-100 dark:from-success-900/20 dark:to-success-800/20 rounded-lg border border-success-200 dark:border-success-700">
                                    <div class="text-xs text-success-700 dark:text-success-300 font-medium mb-1">Bütçe</div>
                                    <div class="text-success-700 dark:text-success-400 font-bold text-xl">
                                        ₺{{ number_format($design['project_budget'], 0) }}
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-col space-y-3">
                                    <!-- Primary Action Button -->
                                    <a href="{{ url('/admin/project-designs/' . $design['id']) }}"
                                       class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-4 py-3 text-sm font-medium text-white shadow-sm hover:bg-primary-500 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200 group">
                                        <x-heroicon-o-eye class="w-4 h-4 group-hover:scale-110 transition-transform duration-200" />
                                        Tasarımı Görüntüle
                                    </a>

                                    <!-- Secondary Actions Row -->
                                    <div class="flex items-center justify-between">
                                        <!-- Like Button with Count -->
                                        <div class="flex items-center space-x-2">
                                            <button
                                                type="button"
                                                wire:click="toggleLike({{ $design['id'] }})"
                                                class="{{ $design['is_liked'] ? 'like-button liked text-red-500' : 'like-button text-gray-400 hover:text-red-500' }} inline-flex items-center justify-center p-2 rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                                title="Beğen / Beğeniyi Kaldır"
                                                aria-pressed="{{ $design['is_liked'] ? 'true' : 'false' }}"
                                            >
                                                @if($design['is_liked'])
                                                    <x-heroicon-s-heart class="w-5 h-5" />
                                                @else
                                                    <x-heroicon-o-heart class="w-5 h-5" />
                                                @endif
                                            </button>
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $design['likes_count'] }}</span>
                                        </div>

                                        <!-- Secondary Action Buttons -->
                                        <div class="flex items-center space-x-1">
                                            <button
                                                type="button"
                                                class="inline-flex items-center justify-center p-2 rounded-full text-gray-400 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                                title="Paylaş"
                                            >
                                                <x-heroicon-o-share class="w-4 h-4" />
                                            </button>
                                            <button
                                                type="button"
                                                class="inline-flex items-center justify-center p-2 rounded-full text-gray-400 hover:text-purple-500 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2"
                                                title="Detaylar"
                                            >
                                                <x-heroicon-o-information-circle class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
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

        /* Line clamp for text truncation */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Improved card hover effects */
        .project-card {
            transform-origin: center;
        }

        .project-card:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Category accordion styles */
        .category-content {
            transition: all 0.3s ease-in-out;
            overflow: hidden;
        }

        .category-content.collapsed {
            display: none !important;
        }

        .category-header:hover .category-chevron {
            transform: scale(1.1);
        }

        /* Enhanced button animations */
        .group:hover .group-hover\:scale-110 {
            transform: scale(1.1);
        }

        /* Smooth transitions for all interactive elements */
        button, a {
            transition: all 0.2s ease-in-out;
        }

        /* Custom scrollbar for better UX */
        .category-content::-webkit-scrollbar {
            width: 6px;
        }

        .category-content::-webkit-scrollbar-track {
            background: transparent;
        }

        .category-content::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.3);
            border-radius: 3px;
        }

        .category-content::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.5);
        }
    </style>

    <script>
        function toggleCategory(index) {
            const content = document.querySelector(`.category-content-${index}`);
            const chevron = document.querySelector(`.category-chevron-${index}`);
            
            if (content.style.display === 'none' || content.classList.contains('collapsed')) {
                content.style.display = 'block';
                content.classList.remove('collapsed');
                chevron.style.transform = 'rotate(0deg)';
            } else {
                content.style.display = 'none';
                content.classList.add('collapsed');
                chevron.style.transform = 'rotate(-90deg)';
            }
        }

        // Initialize all categories as expanded by default
        document.addEventListener('DOMContentLoaded', function() {
            // Add stagger animation to cards
            const cards = document.querySelectorAll('.project-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 50}ms`;
                card.classList.add('animate-fade-in');
            });
        });
    </script>
</x-filament-panels::page>
