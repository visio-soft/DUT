<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Filter Controls -->
        <section class="filter-section">
            <div class="filter-header">
                <x-heroicon-o-funnel class="w-5 h-5" />
                <h3>Filtrele & Sırala</h3>
            </div>
            
            <div class="filter-controls">
                <!-- Sort Options -->
                <div class="control-group">
                    <label>Sırala:</label>
                    <select wire:model.live="sortBy">
                        <option value="newest">En Yeni</option>
                        <option value="popular">En Popüler</option>
                        <option value="price_high">Fiyat (Yüksek-Düşük)</option>
                        <option value="price_low">Fiyat (Düşük-Yüksek)</option>
                    </select>
                </div>

                <!-- Price Filter -->
                <div class="control-group">
                    <label>Fiyat:</label>
                    <select wire:model.live="priceFilter">
                        <option value="">Tümü</option>
                        <option value="0-10000">₺0 - ₺10,000</option>
                        <option value="10000-25000">₺10,000 - ₺25,000</option>
                        <option value="25000-50000">₺25,000 - ₺50,000</option>
                        <option value="50000+">₺50,000+</option>
                    </select>
                </div>
            </div>
        </section>

        <!-- Gallery Section -->
        <section class="gallery-section">
            <div class="section-header">
                <x-heroicon-o-rectangle-stack class="w-6 h-6" />
                <h2>Proje Tasarımları</h2>
            </div>

            <!-- Project Categories -->
            @forelse($this->projectDesigns as $index => $group)
                <article class="category-group" data-category="{{ $index }}">
                    <!-- Category Header -->
                    <header class="category-header" onclick="toggleCategory({{ $index }})">
                        <div class="category-info">
                            <x-heroicon-o-folder class="category-icon" />
                            <div class="category-text">
                                <h3>{{ $group['category_name'] }}</h3>
                                <span class="category-count">{{ count($group['designs']) }} tasarım</span>
                            </div>
                        </div>
                        <x-heroicon-o-chevron-down class="chevron category-chevron-{{ $index }}" />
                    </header>

                    <!-- Projects Grid -->
                    <div style="margin-top: 1rem;" class="projects-grid category-content-{{ $index }}">
                        @foreach($group['designs'] as $design)
                            <div class="project-card" data-project="{{ $design['id'] }}" ondblclick="viewDesign({{ $design['id'] }})" title="Çift tıklayarak detayları görüntüle">
                                <!-- Project Image -->
                                <div class="project-image">
                                    @if($design['project_image'])
                                        <img src="{{ $design['project_image'] }}" alt="{{ $design['project_name'] }}">
                                    @else
                                        <div class="image-placeholder">
                                            <x-heroicon-o-building-office class="placeholder-icon" />
                                        </div>
                                    @endif

                                    <!-- User Badge -->
                                    <div class="user-badge" title="{{ $design['user_name'] ?? 'Kullanıcı' }}">
                                        {{ $design['user_name'] ?? 'Kullanıcı' }}
                                    </div>

                                    <!-- Status Badges -->
                                    <div class="status-badges">
                                        @if($design['likes_count'] >= 3)
                                            <span class="badge badge-trending">
                                                <x-heroicon-s-fire class="badge-icon" />
                                                Trend
                                            </span>
                                        @endif

                                        @if(isset($design['is_latest']) && $design['is_latest'])
                                            <span class="badge badge-new">
                                                <x-heroicon-s-sparkles class="badge-icon" />
                                                Yeni
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Project Info -->
                                <div class="project-info">
                                    <!-- Address -->
                                    <div class="project-address">
                                        <x-heroicon-o-map-pin class="info-icon" />
                                        <span>{{ $design['project_address'] ?: 'Adres bilgisi mevcut değil' }}</span>
                                    </div>

                                    <!-- Budget -->
                                    <div class="project-budget">
                                        <x-heroicon-o-currency-dollar class="info-icon" />
                                        <div class="budget-info">
                                            <span class="budget-label">Tahmini Bütçe</span>
                                            <span class="budget-amount">₺{{ number_format($design['project_budget'], 0) }}</span>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="project-actions">
                                        <a href="{{ url('/admin/project-designs/' . $design['id']) }}" class="btn btn-primary">
                                            <x-heroicon-o-eye class="btn-icon" />
                                            Detayları Gör
                                        </a>

                                        <button type="button" 
                                                wire:click="toggleLike({{ $design['id'] }})"
                                                class="btn btn-like {{ $design['is_liked'] ? 'liked' : '' }}"
                                                title="Beğen / Beğeniyi Kaldır">
                                            @if($design['is_liked'])
                                                <x-heroicon-s-heart class="btn-icon" />
                                            @else
                                                <x-heroicon-o-heart class="btn-icon" />
                                            @endif
                                            <span>{{ $design['likes_count'] }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <x-heroicon-o-paint-brush class="w-12 h-12" />
                    </div>
                    <h3>Henüz tasarım bulunmuyor</h3>
                    <p>İlk proje tasarımını oluşturun ve burada görüntülensin!</p>
                </div>
            @endforelse
        </section>
    </div>

    <style>
        /* Modern CSS for Filament */
        .filter-section {
            background: rgb(var(--gray-50));
            border: 1px solid rgb(var(--gray-200));
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        }

        .dark .filter-section {
            background: rgb(var(--gray-800));
            border-color: rgb(var(--gray-700));
        }

        .filter-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .filter-header h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: rgb(var(--gray-900));
            margin: 0;
        }

        .dark .filter-header h3 {
            color: rgb(var(--gray-50));
        }

        .filter-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .control-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .control-group label {
            font-size: 0.875rem;
            font-weight: 500;
            color: rgb(var(--gray-700));
            white-space: nowrap;
        }

        .dark .control-group label {
            color: rgb(var(--gray-300));
        }

        .control-group select {
            border-radius: 0.5rem;
            border: 1px solid rgb(var(--gray-300));
            background: white;
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
            min-width: 8rem;
            transition: all 0.2s;
        }

        .dark .control-group select {
            border-color: rgb(var(--gray-600));
            background: rgb(var(--gray-700));
            color: rgb(var(--gray-50));
        }

        .control-group select:focus {
            outline: none;
            border-color: rgb(var(--primary-500));
            box-shadow: 0 0 0 2px rgb(var(--primary-500) / 0.2);
        }

        /* Gallery Section */
        .gallery-section {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .section-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: rgb(var(--gray-900));
            margin: 0;
        }

        .dark .section-header h2 {
            color: rgb(var(--gray-50));
        }

        /* Category Groups */
        .category-group {
            background: rgb(var(--gray-50));
            border: 1px solid rgb(var(--gray-200));
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        }

        .dark .category-group {
            background: rgb(var(--gray-800));
            border-color: rgb(var(--gray-700));
        }

        .category-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem;
            background: rgb(var(--gray-100));
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .dark .category-header {
            background: rgb(var(--gray-700) / 0.5);
        }

        .category-header:hover {
            background: rgb(var(--gray-200));
        }

        .dark .category-header:hover {
            background: rgb(var(--gray-700));
        }

        .category-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .category-icon {
            width: 1.25rem;
            height: 1.25rem;
            color: rgb(var(--gray-600));
        }

        .dark .category-icon {
            color: rgb(var(--gray-400));
        }

        .category-text h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: rgb(var(--gray-900));
            margin: 0;
        }

        .dark .category-text h3 {
            color: rgb(var(--gray-50));
        }

        .category-count {
            font-size: 0.875rem;
            color: rgb(var(--gray-500));
        }

        .dark .category-count {
            color: rgb(var(--gray-400));
        }

        .chevron {
            width: 1.25rem;
            height: 1.25rem;
            color: rgb(var(--gray-500));
            transition: transform 0.2s;
        }

        .dark .chevron {
            color: rgb(var(--gray-400));
        }

        /* Projects Grid */
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 1rem;
            padding: 1.5rem;
            padding-top: 0;
            transition: all 0.3s ease;
        }

        @media (min-width: 768px) {
            .projects-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .projects-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (min-width: 1280px) {
            .projects-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        .projects-grid.collapsed {
            display: none;
        }

        /* Project Cards */
        .project-card {
            background: white;
            border: 1px solid rgb(var(--gray-200));
            border-radius: 0.5rem;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            cursor: pointer;
        }

        .dark .project-card {
            background: rgb(var(--gray-800));
            border-color: rgb(var(--gray-700));
        }

        .project-card:hover {
            box-shadow: 0 10px 25px -3px rgb(0 0 0 / 0.15), 0 4px 6px -2px rgb(0 0 0 / 0.05);
            transform: translateY(-4px) scale(1.02);
            border-color: rgb(var(--primary-300));
        }

        .dark .project-card:hover {
            box-shadow: 0 10px 25px -3px rgb(0 0 0 / 0.3), 0 4px 6px -2px rgb(0 0 0 / 0.1);
            border-color: rgb(var(--primary-600));
        }

        .project-image {
            position: relative;
            height: 12rem;
            background: rgb(var(--gray-100));
            overflow: hidden;
        }

        .dark .project-image {
            background: rgb(var(--gray-700));
        }

        .project-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .project-card:hover .project-image img {
            transform: scale(1.05);
        }

        .image-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom right, rgb(var(--primary-400)), rgb(var(--primary-600)));
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .placeholder-icon {
            width: 3rem;
            height: 3rem;
            color: white;
        }

        .user-badge {
            position: absolute;
            top: 0.75rem;
            left: 0.75rem;
            background: rgb(0 0 0 / 0.6);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            backdrop-filter: blur(4px);
            transition: all 0.3s ease;
        }

        .project-card:hover .user-badge {
            background: rgb(0 0 0 / 0.8);
            transform: translateY(-2px);
        }

        .status-badges {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .badge {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: white;
            backdrop-filter: blur(4px);
            transition: all 0.3s ease;
        }

        .project-card:hover .badge {
            transform: translateY(-2px) scale(1.05);
        }
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: white;
            backdrop-filter: blur(4px);
        }

        .badge-trending {
            background: rgb(var(--warning-500));
        }

        .badge-new {
            background: rgb(var(--info-500));
        }

        .badge-icon {
            width: 0.75rem;
            height: 0.75rem;
        }

        /* Project Info */
        .project-info {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .project-card:hover .project-info {
            transform: translateY(-2px);
        }

        .project-title {
            font-weight: 600;
            color: rgb(var(--gray-900));
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dark .project-title {
            color: rgb(var(--gray-50));
        }

        .project-address,
        .project-budget {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .info-icon {
            width: 1rem;
            height: 1rem;
            color: rgb(var(--gray-500));
            flex-shrink: 0;
        }

        .dark .info-icon {
            color: rgb(var(--gray-400));
        }

        .project-address span {
            color: rgb(var(--gray-600));
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dark .project-address span {
            color: rgb(var(--gray-300));
        }

        .budget-info {
            display: flex;
            flex-direction: column;
        }

        .budget-label {
            font-size: 0.75rem;
            color: rgb(var(--gray-500));
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .dark .budget-label {
            color: rgb(var(--gray-400));
        }

        .budget-amount {
            font-weight: 700;
            color: rgb(var(--success-600));
        }

        .dark .budget-amount {
            color: rgb(var(--success-400));
        }

        /* Action Buttons */
        .project-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgb(var(--primary-500) / 0.2);
        }

        .btn-primary {
            flex: 1;
            background: rgb(var(--primary-600));
            color: white;
            text-decoration: none;
            justify-content: center;
        }

        .btn-primary:hover {
            background: rgb(var(--primary-700));
        }

        .btn-like {
            color: rgb(var(--gray-500));
            background: transparent;
        }

        .btn-like:hover {
            color: rgb(var(--danger-500));
            background: rgb(var(--danger-50));
        }

        .dark .btn-like:hover {
            background: rgb(var(--danger-900) / 0.2);
        }

        .btn-like.liked {
            color: rgb(var(--danger-500));
            background: rgb(var(--danger-50));
        }

        .dark .btn-like.liked {
            background: rgb(var(--danger-900) / 0.2);
        }

        .btn-icon {
            width: 1rem;
            height: 1rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-icon {
            width: 4rem;
            height: 4rem;
            background: rgb(var(--gray-100));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: rgb(var(--gray-400));
        }

        .dark .empty-icon {
            background: rgb(var(--gray-700));
            color: rgb(var(--gray-500));
        }

        .empty-state h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: rgb(var(--gray-900));
            margin: 0 0 0.5rem 0;
        }

        .dark .empty-state h3 {
            color: rgb(var(--gray-50));
        }

        .empty-state p {
            color: rgb(var(--gray-500));
            margin: 0;
        }

        .dark .empty-state p {
            color: rgb(var(--gray-400));
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .filter-controls {
                flex-direction: column;
            }
            
            .control-group {
                width: 100%;
            }
            
            .control-group select {
                width: 100%;
            }
        }
    </style>

    <script>
        // Modern JavaScript with improved functionality
        class ProjectGallery {
            constructor() {
                this.init();
            }

            init() {
                this.bindEvents();
                this.animateOnLoad();
            }

            bindEvents() {
                // Like button interactions
                document.addEventListener('click', (e) => {
                    if (e.target.closest('.btn-like')) {
                        this.handleLikeClick(e.target.closest('.btn-like'));
                    }
                });
            }

            handleLikeClick(button) {
                const icon = button.querySelector('.btn-icon');
                if (icon) {
                    icon.style.transform = 'scale(1.3)';
                    setTimeout(() => {
                        icon.style.transform = 'scale(1)';
                    }, 200);
                }
            }

            animateOnLoad() {
                // Staggered animation for project cards
                const cards = document.querySelectorAll('.project-card');
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    
                    setTimeout(() => {
                        card.style.transition = 'all 0.4s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            }
        }

        // Category toggle functionality
        function toggleCategory(index) {
            const content = document.querySelector(`.category-content-${index}`);
            const chevron = document.querySelector(`.category-chevron-${index}`);
            
            if (!content || !chevron) return;
            
            const isHidden = content.classList.contains('collapsed');
            
            if (isHidden) {
                content.classList.remove('collapsed');
                chevron.style.transform = 'rotate(0deg)';
            } else {
                content.classList.add('collapsed');
                chevron.style.transform = 'rotate(-90deg)';
            }
        }

        // Design viewing functionality
        function viewDesign(designId) {
            window.location.href = `/admin/project-designs/${designId}`;
        }

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            new ProjectGallery();
        });
    </script>
</x-filament-panels::page>