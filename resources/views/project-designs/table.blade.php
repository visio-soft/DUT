<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proje Tasarımları</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .like-btn {
            transition: all 0.3s ease;
        }
        .like-btn:hover {
            transform: scale(1.1);
        }
        .like-btn.liked {
            color: #ef4444;
        }
        .like-btn.not-liked {
            color: #6b7280;
        }
        .project-card {
            transition: all 0.3s ease;
        }
        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .project-image {
            height: 200px;
            object-fit: cover;
            width: 100%;
            transition: transform 0.3s ease;
        }
        .project-image:hover {
            transform: scale(1.05);
        }
        .badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
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
            animation: fadeIn 0.5s ease forwards;
        }

        .loading-spinner {
            border: 4px solid #f3f4f6;
            border-left: 4px solid #3b82f6;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

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
        }

        .project-card:hover::before {
            transform: translateX(100%);
        }

        .dark-mode-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    <!-- Dark Mode Toggle -->
    <button id="darkModeToggle" class="dark-mode-toggle bg-white dark:bg-gray-800 p-3 rounded-full shadow-lg hover:shadow-xl transition-all">
        <i class="fas fa-moon dark:hidden text-gray-700"></i>
        <i class="fas fa-sun hidden dark:block text-yellow-400"></i>
    </button>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-2">Proje Tasarımları</h1>
            <p class="text-gray-600 dark:text-gray-300 mb-6">En güzel proje tasarımlarını keşfedin ve beğenin</p>

            <!-- Stats -->
            <div class="flex justify-center space-x-6 mb-6">
                <div class="stat-card rounded-lg px-4 py-2 bg-white dark:bg-gray-800">
                    <div class="text-2xl font-bold text-blue-600">{{ count($projectDesigns) }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">Toplam Tasarım</div>
                </div>
                <div class="stat-card rounded-lg px-4 py-2 bg-white dark:bg-gray-800">
                    <div class="text-2xl font-bold text-red-600">{{ $projectDesigns->sum('likes_count') }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">Toplam Beğeni</div>
                </div>
            </div>

            <!-- Filter and Sort Controls -->
            <div class="flex flex-col md:flex-row justify-center items-center space-y-4 md:space-y-0 md:space-x-4 mb-8">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-search text-gray-400"></i>
                    <input type="text" id="searchInput" placeholder="Proje adında ara..."
                           class="bg-white dark:bg-gray-800 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 w-64 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <select id="sortFilter" class="bg-white dark:bg-gray-800 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="newest">En Yeni</option>
                    <option value="popular">En Popüler</option>
                    <option value="budget-high">Bütçe (Yüksek-Düşük)</option>
                    <option value="budget-low">Bütçe (Düşük-Yüksek)</option>
                </select>

                <div class="flex items-center space-x-2">
                    <label class="text-gray-600 dark:text-gray-300 whitespace-nowrap">Min. Bütçe:</label>
                    <input type="number" id="minBudget" placeholder="0"
                           class="bg-white dark:bg-gray-800 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 w-24 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <button id="clearFilters" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-times mr-1"></i>
                    Temizle
                </button>
            </div>

            <!-- Results Counter -->
            <div id="resultsCounter" class="text-center text-gray-600 dark:text-gray-300 mb-4">
                <span id="visibleCount">{{ count($projectDesigns) }}</span> tasarım görüntüleniyor
            </div>
        </div>

        <!-- Projects Grid -->
        <div id="projectsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($projectDesigns as $design)
                <div class="project-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-colors duration-300"
                     data-likes="{{ $design['likes_count'] }}"
                     data-budget="{{ $design['project_budget'] }}"
                     data-name="{{ $design['project_name'] }}"
                     data-created="{{ $design['created_at'] }}">
                    <!-- Project Image -->
                    <div class="relative overflow-hidden">
                        @if($design['project_image'])
                            <img src="{{ $design['project_image'] }}"
                                 alt="{{ $design['project_name'] }}"
                                 class="project-image">
                        @else
                            <div class="project-image bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                <i class="fas fa-image text-white text-4xl"></i>
                            </div>
                        @endif

                        <!-- Like Button Overlay -->
                        <button class="like-btn absolute top-3 right-3 bg-white bg-opacity-80 rounded-full p-2 {{ $design['is_liked'] ? 'liked' : 'not-liked' }}"
                                data-design-id="{{ $design['id'] }}"
                                onclick="toggleLike(this, {{ $design['id'] }})">
                            <i class="fas fa-heart text-lg"></i>
                        </button>

                        <!-- Popular Badge -->
                        @if($design['likes_count'] >= 3)
                            <div class="absolute top-3 left-3 badge">
                                <i class="fas fa-star mr-1"></i>
                                Popüler
                            </div>
                        @endif
                    </div>

                    <!-- Project Info -->
                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-gray-800 dark:text-white mb-2 truncate" title="{{ $design['project_name'] }}">
                            {{ $design['project_name'] }}
                        </h3>

                        <div class="flex items-center justify-between mb-3">
                            <span class="text-green-600 font-bold text-lg">
                                ₺{{ number_format($design['project_budget'], 0) }}
                            </span>
                            <div class="flex items-center text-gray-500">
                                <i class="fas fa-heart text-red-400 mr-1"></i>
                                <span class="likes-count font-semibold">{{ $design['likes_count'] }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            <a href="{{ url('/admin/project-design-resources/' . $design['id']) }}"
                               class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-3 rounded-md text-sm font-medium text-center transition-colors">
                                <i class="fas fa-eye mr-1"></i>
                                Tasarımı Görüntüle
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-paint-brush text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-500 mb-2">Henüz tasarım bulunmuyor</h3>
                    <p class="text-gray-400">İlk proje tasarımını oluşturun!</p>
                </div>
            @endforelse
        </div>

        <!-- Back to Admin Button -->
        <div class="mt-12 text-center">
            <a href="/admin" class="inline-flex items-center px-6 py-3 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Admin Paneline Dön
            </a>
        </div>
    </div>

    <script>
        // Set up CSRF token for AJAX requests
        window.axios = window.axios || {};
        window.axios.defaults = window.axios.defaults || {};
        window.axios.defaults.headers = window.axios.defaults.headers || {};
        window.axios.defaults.headers.common = window.axios.defaults.headers.common || {};
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function toggleLike(button, designId) {
            const isLiked = button.classList.contains('liked');
            const likesCountElement = button.closest('.project-card').querySelector('.likes-count');

            // Disable button during request
            button.disabled = true;

            // Make AJAX request
            fetch(`/project-designs/${designId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                // Update button state
                if (data.liked) {
                    button.classList.remove('not-liked');
                    button.classList.add('liked');
                } else {
                    button.classList.remove('liked');
                    button.classList.add('not-liked');
                }

                // Update likes count
                likesCountElement.textContent = data.likes_count;

                // Add animation effect
                button.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    button.style.transform = 'scale(1)';
                }, 200);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            })
            .finally(() => {
                button.disabled = false;
            });
        }

        // Add some loading animation when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.project-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Initialize sorting and filtering
            initializeSortingAndFiltering();
            initializeDarkMode();
        });

        function initializeDarkMode() {
            const darkModeToggle = document.getElementById('darkModeToggle');
            const html = document.documentElement;

            // Check for saved dark mode preference or default to light mode
            const savedTheme = localStorage.getItem('darkMode');
            if (savedTheme === 'true') {
                html.classList.add('dark');
            }

            darkModeToggle.addEventListener('click', () => {
                html.classList.toggle('dark');
                localStorage.setItem('darkMode', html.classList.contains('dark'));
            });
        }

        function initializeSortingAndFiltering() {
            const searchInput = document.getElementById('searchInput');
            const sortFilter = document.getElementById('sortFilter');
            const minBudgetFilter = document.getElementById('minBudget');
            const clearFiltersBtn = document.getElementById('clearFilters');
            const projectsGrid = document.getElementById('projectsGrid');

            function applyFiltersAndSorting() {
                const cards = Array.from(projectsGrid.children);
                const sortValue = sortFilter.value;
                const minBudget = parseFloat(minBudgetFilter.value) || 0;
                const searchTerm = searchInput.value.toLowerCase().trim();

                let visibleCount = 0;

                // Filter cards
                cards.forEach(card => {
                    const budget = parseFloat(card.dataset.budget) || 0;
                    const projectName = card.dataset.name.toLowerCase();

                    const matchesSearch = !searchTerm || projectName.includes(searchTerm);
                    const matchesBudget = budget >= minBudget;

                    if (matchesSearch && matchesBudget) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Show/hide no results message and update counter
                updateNoResultsMessage(visibleCount);
                updateResultsCounter(visibleCount);

                // Get visible cards for sorting
                const visibleCards = cards.filter(card => card.style.display !== 'none');

                // Sort cards
                visibleCards.sort((a, b) => {
                    switch (sortValue) {
                        case 'popular':
                            return parseInt(b.dataset.likes) - parseInt(a.dataset.likes);
                        case 'budget-high':
                            return parseFloat(b.dataset.budget) - parseFloat(a.dataset.budget);
                        case 'budget-low':
                            return parseFloat(a.dataset.budget) - parseFloat(b.dataset.budget);
                        case 'newest':
                            return parseInt(b.dataset.created) - parseInt(a.dataset.created);
                        default:
                            return 0;
                    }
                });

                // Reorder the DOM
                visibleCards.forEach(card => {
                    projectsGrid.appendChild(card);
                });

                // Add stagger animation
                visibleCards.forEach((card, index) => {
                    card.style.animationDelay = `${index * 50}ms`;
                    card.classList.add('animate-fade-in');
                });
            }

            function updateNoResultsMessage(visibleCount) {
                let noResultsMsg = document.getElementById('noResultsMessage');

                if (visibleCount === 0) {
                    if (!noResultsMsg) {
                        noResultsMsg = document.createElement('div');
                        noResultsMsg.id = 'noResultsMessage';
                        noResultsMsg.className = 'col-span-full text-center py-12';
                        noResultsMsg.innerHTML = `
                            <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-500 mb-2">Sonuç bulunamadı</h3>
                            <p class="text-gray-400">Arama kriterlerinizi değiştirmeyi deneyin.</p>
                        `;
                        projectsGrid.appendChild(noResultsMsg);
                    }
                } else {
                    if (noResultsMsg) {
                        noResultsMsg.remove();
                    }
                }
            }

            function updateResultsCounter(visibleCount) {
                const counter = document.getElementById('visibleCount');
                if (counter) {
                    counter.textContent = visibleCount;
                }
            }

            function clearAllFilters() {
                searchInput.value = '';
                sortFilter.value = 'newest';
                minBudgetFilter.value = '';
                applyFiltersAndSorting();
            }

            // Event listeners
            searchInput.addEventListener('input', debounce(applyFiltersAndSorting, 300));
            sortFilter.addEventListener('change', applyFiltersAndSorting);
            minBudgetFilter.addEventListener('input', debounce(applyFiltersAndSorting, 300));
            clearFiltersBtn.addEventListener('click', clearAllFilters);

            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }
        }
    </script>
</body>
</html>
