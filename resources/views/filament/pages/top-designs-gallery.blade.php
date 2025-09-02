<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header Section -->
        <section class="header-section">
            <div class="header-content">
                <div class="header-icon">
                    <x-heroicon-o-trophy class="w-8 h-8" />
                </div>
                <div class="header-text">
                    <h1>En İyi Tasarımlar</h1>
                    <p>Her kategoride en çok beğeni alan ilk 3 tasarım</p>
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        <section class="categories-section">
            @forelse($this->topDesigns as $index => $group)
                <article class="category-group" data-category="{{ $index }}">
                    <!-- Category Header -->
                    <header class="category-header" onclick="toggleCategory({{ $index }})">
                        <div class="category-info">
                            <x-heroicon-o-folder class="category-icon" />
                            <div class="category-text">
                                <h2>{{ $group['category_name'] }}</h2>
                                <span class="category-subtitle">En İyi {{ count($group['designs']) }} Tasarım</span>
                            </div>
                        </div>
                        <x-heroicon-o-chevron-down class="chevron category-chevron-{{ $index }}" />
                    </header>

                    <!-- Rankings Grid -->
                    <div class="rankings-grid category-content-{{ $index }}">
                        @foreach($group['designs'] as $design)
                            <div class="ranking-card rank-{{ $design['rank'] }} {{ $design['rank_class'] }}" 
                                 data-design="{{ $design['id'] }}" 
                                 onclick="viewDesign({{ $design['id'] }})"
                                 title="Detayları görüntülemek için tıklayın">
                                
                                <!-- Rank Badge -->
                                <div class="rank-badge rank-{{ $design['rank'] }}">
                                    <span class="rank-number">{{ $design['rank'] }}</span>
                                    <span class="rank-text">{{ $design['rank_text'] }}</span>
                                </div>

                                <!-- Design Image -->
                                <div class="design-image">
                                    @if($design['project_image'])
                                        <img src="{{ $design['project_image'] }}" alt="{{ $design['project_name'] }}">
                                    @else
                                        <div class="image-placeholder">
                                            <x-heroicon-o-building-office class="placeholder-icon" />
                                        </div>
                                    @endif

                                    <!-- Likes Badge -->
                                    <div class="likes-badge">
                                        <x-heroicon-s-heart class="likes-icon" />
                                        <span>{{ $design['likes_count'] }}</span>
                                    </div>
                                </div>

                                <!-- Design Info -->
                                <div class="design-info">
                                    <!-- Project Title -->
                                    <h3 class="project-title">{{ $design['project_name'] }}</h3>

                                    <!-- User -->
                                    <div class="project-user">
                                        <x-heroicon-o-user class="info-icon" />
                                        <span>{{ $design['user_name'] }}</span>
                                    </div>

                                    <!-- Address -->
                                    <div class="project-address">
                                        <x-heroicon-o-map-pin class="info-icon" />
                                        <span>{{ $design['project_address'] ?: 'Adres bilgisi mevcut değil' }}</span>
                                    </div>

                                    <!-- Budget -->
                                    <div class="project-budget">
                                        <x-heroicon-o-currency-dollar class="info-icon" />
                                        <div class="budget-info">
                                            <span class="budget-label">Bütçe</span>
                                            <span class="budget-amount">₺{{ number_format($design['project_budget'], 0) }}</span>
                                        </div>
                                    </div>

                                    <!-- Action -->
                                    <div class="design-action">
                                        <a href="{{ url('/admin/project-designs/' . $design['id']) }}" class="btn btn-view">
                                            <x-heroicon-o-eye class="btn-icon" />
                                            Detayları Gör
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <x-heroicon-o-trophy class="w-16 h-16" />
                    </div>
                    <h3>Henüz tasarım yarışması yok</h3>
                    <p>İlk tasarımlar oluşturulduğunda burada sıralama görünecektir!</p>
                </div>
            @endforelse
        </section>
    </div>

    <style>
        /* Modern Championship Design System */
        .header-section {
            background: linear-gradient(135deg, rgb(var(--warning-400)) 0%, rgb(var(--warning-600)) 100%);
            border-radius: 1rem;
            padding: 2rem;
            color: white;
            box-shadow: 0 10px 25px -3px rgb(var(--warning-500) / 0.3);
            position: relative;
            overflow: hidden;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: rgb(255 255 255 / 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .header-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 150px;
            height: 150px;
            background: rgb(255 255 255 / 0.1);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .header-icon {
            background: rgb(255 255 255 / 0.2);
            border-radius: 1rem;
            padding: 1rem;
            backdrop-filter: blur(10px);
        }

        .header-text h1 {
            font-size: 2rem;
            font-weight: 800;
            margin: 0 0 0.5rem 0;
            text-shadow: 0 2px 4px rgb(0 0 0 / 0.1);
        }

        .header-text p {
            font-size: 1.125rem;
            margin: 0;
            opacity: 0.9;
        }

        /* Categories Section */
        .categories-section {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .category-group {
            background: rgb(var(--gray-50));
            border: 1px solid rgb(var(--gray-200));
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            transition: all 0.3s ease;
        }

        .dark .category-group {
            background: rgb(var(--gray-800));
            border-color: rgb(var(--gray-700));
        }

        .category-group:hover {
            box-shadow: 0 10px 25px -3px rgb(0 0 0 / 0.15);
            transform: translateY(-2px);
        }

        .category-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, rgb(var(--primary-500)) 0%, rgb(var(--primary-600)) 100%);
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .category-header:hover {
            background: linear-gradient(135deg, rgb(var(--primary-600)) 0%, rgb(var(--primary-700)) 100%);
        }

        .category-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .category-icon {
            width: 1.5rem;
            height: 1.5rem;
        }

        .category-text h2 {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
        }

        .category-subtitle {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        .chevron {
            width: 1.25rem;
            height: 1.25rem;
            transition: transform 0.3s ease;
        }

        /* Rankings Grid */
        .rankings-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 1.5rem;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        @media (min-width: 768px) {
            .rankings-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .rankings-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        .rankings-grid.collapsed {
            display: none;
        }

        /* Ranking Cards */
        .ranking-card {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            position: relative;
            transition: all 0.4s ease;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .dark .ranking-card {
            background: rgb(var(--gray-800));
        }

        .ranking-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px -12px rgb(0 0 0 / 0.25);
        }

        /* Winner Effects */
        .ranking-card.winner {
            border: 3px solid rgb(var(--warning-400));
            box-shadow: 0 0 30px rgb(var(--warning-400) / 0.3);
        }

        .ranking-card.winner:hover {
            box-shadow: 0 0 40px rgb(var(--warning-400) / 0.5), 0 20px 40px -12px rgb(0 0 0 / 0.25);
        }

        .ranking-card.second {
            border: 3px solid rgb(var(--gray-400));
            box-shadow: 0 0 20px rgb(var(--gray-400) / 0.2);
        }

        .ranking-card.third {
            border: 3px solid rgb(var(--orange-400));
            box-shadow: 0 0 20px rgb(var(--orange-400) / 0.2);
        }

        /* Rank Badge */
        .rank-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
            backdrop-filter: blur(10px);
            border-radius: 0.75rem;
            padding: 0.75rem;
            font-weight: 700;
            text-align: center;
            transition: all 0.3s ease;
        }

        .ranking-card:hover .rank-badge {
            transform: scale(1.1);
        }

        .rank-badge.rank-1 {
            background: linear-gradient(135deg, rgb(var(--warning-400)), rgb(var(--warning-600)));
            color: white;
            box-shadow: 0 4px 15px rgb(var(--warning-500) / 0.4);
        }

        .rank-badge.rank-2 {
            background: linear-gradient(135deg, rgb(var(--gray-400)), rgb(var(--gray-600)));
            color: white;
            box-shadow: 0 4px 15px rgb(var(--gray-500) / 0.4);
        }

        .rank-badge.rank-3 {
            background: linear-gradient(135deg, rgb(var(--orange-400)), rgb(var(--orange-600)));
            color: white;
            box-shadow: 0 4px 15px rgb(var(--orange-500) / 0.4);
        }

        .rank-number {
            font-size: 1.5rem;
            font-weight: 900;
        }

        .rank-text {
            font-size: 0.625rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }

        /* Design Image */
        .design-image {
            position: relative;
            height: 14rem;
            background: rgb(var(--gray-100));
            overflow: hidden;
        }

        .dark .design-image {
            background: rgb(var(--gray-700));
        }

        .design-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .ranking-card:hover .design-image img {
            transform: scale(1.1);
        }

        .image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgb(var(--primary-400)), rgb(var(--primary-600)));
        }

        .placeholder-icon {
            width: 4rem;
            height: 4rem;
            color: white;
        }

        /* Likes Badge */
        .likes-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgb(0 0 0 / 0.7);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            backdrop-filter: blur(8px);
            transition: all 0.3s ease;
        }

        .ranking-card:hover .likes-badge {
            background: rgb(var(--danger-500));
            transform: scale(1.05);
        }

        .likes-icon {
            width: 1rem;
            height: 1rem;
        }

        /* Design Info */
        .design-info {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .project-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: rgb(var(--gray-900));
            margin: 0;
            line-clamp: 2;
            -webkit-line-clamp: 2;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .dark .project-title {
            color: rgb(var(--gray-50));
        }

        .project-user,
        .project-address,
        .project-budget {
            display: flex;
            align-items: center;
            gap: 0.75rem;
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

        .project-user span,
        .project-address span {
            color: rgb(var(--gray-600));
            line-clamp: 1;
            -webkit-line-clamp: 1;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .dark .project-user span,
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
            font-size: 1rem;
        }

        .dark .budget-amount {
            color: rgb(var(--success-400));
        }

        /* Action Button */
        .design-action {
            margin-top: 0.5rem;
        }

        .btn-view {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgb(var(--primary-600));
            color: white;
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-view:hover {
            background: rgb(var(--primary-700));
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgb(var(--primary-600) / 0.3);
        }

        .btn-icon {
            width: 1rem;
            height: 1rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: rgb(var(--gray-50));
            border-radius: 1rem;
            border: 2px dashed rgb(var(--gray-300));
        }

        .dark .empty-state {
            background: rgb(var(--gray-800));
            border-color: rgb(var(--gray-600));
        }

        .empty-icon {
            color: rgb(var(--gray-400));
            margin: 0 auto 1.5rem;
        }

        .dark .empty-icon {
            color: rgb(var(--gray-500));
        }

        .empty-state h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: rgb(var(--gray-900));
            margin: 0 0 1rem 0;
        }

        .dark .empty-state h3 {
            color: rgb(var(--gray-50));
        }

        .empty-state p {
            color: rgb(var(--gray-500));
            font-size: 1.125rem;
            margin: 0;
        }

        .dark .empty-state p {
            color: rgb(var(--gray-400));
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .header-text h1 {
                font-size: 1.75rem;
            }
            
            .rankings-grid {
                padding: 1rem;
            }
            
            .category-header {
                padding: 1rem 1.5rem;
            }
        }

        /* Animation for cards on load */
        .ranking-card {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards;
        }

        .ranking-card:nth-child(1) { animation-delay: 0.1s; }
        .ranking-card:nth-child(2) { animation-delay: 0.2s; }
        .ranking-card:nth-child(3) { animation-delay: 0.3s; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <script>
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

        // Add sparkle animation for winner cards
        function addSparkleEffect() {
            const winnerCards = document.querySelectorAll('.ranking-card.winner');
            
            winnerCards.forEach(card => {
                // Create sparkle elements
                for (let i = 0; i < 3; i++) {
                    const sparkle = document.createElement('div');
                    sparkle.className = 'sparkle';
                    sparkle.style.cssText = `
                        position: absolute;
                        width: 4px;
                        height: 4px;
                        background: rgb(var(--warning-300));
                        border-radius: 50%;
                        pointer-events: none;
                        animation: sparkle 2s linear infinite;
                        animation-delay: ${i * 0.6}s;
                        top: ${Math.random() * 100}%;
                        left: ${Math.random() * 100}%;
                        z-index: 1;
                    `;
                    card.appendChild(sparkle);
                }
            });
        }

        // Add sparkle animation CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes sparkle {
                0%, 100% { 
                    opacity: 0; 
                    transform: scale(0); 
                }
                50% { 
                    opacity: 1; 
                    transform: scale(1); 
                }
            }
        `;
        document.head.appendChild(style);

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            addSparkleEffect();
        });
    </script>
</x-filament-panels::page>
