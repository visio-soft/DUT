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
                                <h3>{{ $group['category_name'] }} </h3>
                                <span class="category-count">{{ count($group['designs']) }} Öneri</span>

                                @php
                                    // Ensure timezone consistency - Parse times in Istanbul timezone
                                    $startIso = null;
                                    $endIso = null;

                                    if (isset($group['start_datetime']) && $group['start_datetime']) {
                                        try {
                                            $startCarbon = \Carbon\Carbon::parse($group['start_datetime'], 'Europe/Istanbul');
                                            $startIso = $startCarbon->toIso8601String();
                                        } catch (Exception $e) {
                                            $startIso = null;
                                        }
                                    }

                                    if (isset($group['end_datetime']) && $group['end_datetime']) {
                                        try {
                                            $endCarbon = \Carbon\Carbon::parse($group['end_datetime'], 'Europe/Istanbul');
                                            $endIso = $endCarbon->toIso8601String();
                                        } catch (Exception $e) {
                                            $endIso = null;
                                        }
                                    }
                                @endphp

                                <span
                                    id="countdown-{{ $index }}"
                                    class="category-countdown"
                                    data-start="{{ $startIso }}"
                                    data-end="{{ $endIso }}"
                                    aria-live="polite"
                                    title="Kalan süre"
                                >
                                {{-- JS will populate this --}}
                                </span>
                            </div>
                        </div>

                        <x-heroicon-o-chevron-down class="chevron category-chevron-{{ $index }}" />
                    </header>

                    @php
                        // Check if voting period has ended for this category
                        $now = now();

                        // Parse end_datetime if it's a string
                        $endDateTime = null;
                        if (isset($group['end_datetime']) && $group['end_datetime']) {
                            try {
                                $endDateTime = \Carbon\Carbon::parse($group['end_datetime']);
                            } catch (Exception $e) {
                                $endDateTime = null;
                            }
                        }

                        $votingEnded = $endDateTime && $now->greaterThan($endDateTime);
                        $topDesigns = $votingEnded ? collect($group['designs'])->sortByDesc('likes_count')->take(3)->values() : collect();
                        $otherDesigns = $votingEnded ? collect($group['designs'])->sortByDesc('likes_count')->slice(3) : collect();

                        // Debug log
                        \Log::info('Category voting check', [
                            'category' => $group['category_name'] ?? 'Unknown',
                            'index' => $index,
                            'now' => $now->toISOString(),
                            'end_datetime_raw' => $group['end_datetime'] ?? 'null',
                            'end_datetime_parsed' => $endDateTime ? $endDateTime->toISOString() : 'null',
                            'end_datetime_exists' => isset($group['end_datetime']),
                            'end_datetime_truthy' => !empty($group['end_datetime']),
                            'now_greater_than_end' => $endDateTime ? $now->greaterThan($endDateTime) : 'N/A',
                            'voting_ended' => $votingEnded,
                            'designs_count' => count($group['designs'] ?? []),
                            'top_designs_count' => $topDesigns->count()
                        ]);
                    @endphp

                    @if($votingEnded && $topDesigns->count() >= 1)
                        <!-- Winners Layout - Similar to Top Designs Gallery -->
                        <div class="category-content-{{ $index }} winners-category-section">
                            <!-- Competition Results Header -->
                            <div class="competition-results-header">
                                <x-heroicon-o-trophy class="w-8 h-8" />
                                <div class="header-text">
                                    <h4>Yarışma Sonuçları</h4>
                                    <p>En çok beğeni alan ilk 3 tasarım</p>
                                </div>
                                <span class="results-badge">Kazananlar Açıklandı</span>
                            </div>

                            <!-- Top 3 Winners Grid -->
                            <div class="winners-rankings-grid">
                                <!-- 2nd Place (Left) -->
                                @if($topDesigns->get(1))
                                    @php $design = $topDesigns->get(1); @endphp
                                    <div class="winner-ranking-card rank-2 second"
                                         data-design="{{ $design['id'] }}"
                                         onclick="viewDesign({{ $design['id'] }})"
                                         title="2. Sıra - {{ $design['likes_count'] }} beğeni">

                                        <!-- Rank Badge -->
                                        <div class="rank-badge rank-2">
                                            <span class="rank-number">2</span>
                                            <span class="rank-text">İKİNCİ</span>
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
                                            <h3 class="project-title">{{ $design['project_name'] }}</h3>

                                            <div class="project-user">
                                                <x-heroicon-o-user class="info-icon" />
                                                <span>{{ $design['user_name'] }}</span>
                                            </div>

                                            <div class="project-address">
                                                <x-heroicon-o-map-pin class="info-icon" />
                                                <span>{{ $design['project_address'] ?: 'Adres bilgisi mevcut değil' }}</span>
                                            </div>

                                            <div class="project-budget">
                                                <x-heroicon-o-currency-dollar class="info-icon" />
                                                <div class="budget-info">
                                                    <span class="budget-label">Bütçe</span>
                                                    <span class="budget-amount">₺{{ number_format($design['project_budget'], 0) }}</span>
                                                </div>
                                            </div>

                                            <div class="design-action">
                                                <a href="{{ url('/admin/project-designs/' . $design['id']) }}" class="btn btn-view">
                                                    <x-heroicon-o-eye class="btn-icon" />
                                                    Detayları Gör
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- 1st Place (Center) -->
                                @if($topDesigns->get(0))
                                    @php $design = $topDesigns->get(0); @endphp
                                    <div class="winner-ranking-card rank-1 winner"
                                         data-design="{{ $design['id'] }}"
                                         onclick="viewDesign({{ $design['id'] }})"
                                         title="1. Sıra - {{ $design['likes_count'] }} beğeni">

                                        <!-- Rank Badge -->
                                        <div class="rank-badge rank-1">
                                            <span class="rank-number">1</span>
                                            <span class="rank-text">BİRİNCİ</span>
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
                                            <h3 class="project-title">{{ $design['project_name'] }}</h3>

                                            <div class="project-user">
                                                <x-heroicon-o-user class="info-icon" />
                                                <span>{{ $design['user_name'] }}</span>
                                            </div>

                                            <div class="project-address">
                                                <x-heroicon-o-map-pin class="info-icon" />
                                                <span>{{ $design['project_address'] ?: 'Adres bilgisi mevcut değil' }}</span>
                                            </div>

                                            <div class="project-budget">
                                                <x-heroicon-o-currency-dollar class="info-icon" />
                                                <div class="budget-info">
                                                    <span class="budget-label">Bütçe</span>
                                                    <span class="budget-amount">₺{{ number_format($design['project_budget'], 0) }}</span>
                                                </div>
                                            </div>

                                            <div class="design-action">
                                                <a href="{{ url('/admin/project-designs/' . $design['id']) }}" class="btn btn-view">
                                                    <x-heroicon-o-eye class="btn-icon" />
                                                    Detayları Gör
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- 3rd Place (Right) -->
                                @if($topDesigns->get(2))
                                    @php $design = $topDesigns->get(2); @endphp
                                    <div class="winner-ranking-card rank-3 third"
                                         data-design="{{ $design['id'] }}"
                                         onclick="viewDesign({{ $design['id'] }})"
                                         title="3. Sıra - {{ $design['likes_count'] }} beğeni">

                                        <!-- Rank Badge -->
                                        <div class="rank-badge rank-3">
                                            <span class="rank-number">3</span>
                                            <span class="rank-text">ÜÇÜNCÜ</span>
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
                                            <h3 class="project-title">{{ $design['project_name'] }}</h3>

                                            <div class="project-user">
                                                <x-heroicon-o-user class="info-icon" />
                                                <span>{{ $design['user_name'] }}</span>
                                            </div>

                                            <div class="project-address">
                                                <x-heroicon-o-map-pin class="info-icon" />
                                                <span>{{ $design['project_address'] ?: 'Adres bilgisi mevcut değil' }}</span>
                                            </div>

                                            <div class="project-budget">
                                                <x-heroicon-o-currency-dollar class="info-icon" />
                                                <div class="budget-info">
                                                    <span class="budget-label">Bütçe</span>
                                                    <span class="budget-amount">₺{{ number_format($design['project_budget'], 0) }}</span>
                                                </div>
                                            </div>

                                            <div class="design-action">
                                                <a href="{{ url('/admin/project-designs/' . $design['id']) }}" class="btn btn-view">
                                                    <x-heroicon-o-eye class="btn-icon" />
                                                    Detayları Gör
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Other Designs Dropdown Section -->
                            @if($otherDesigns->count() > 0)
                                <div class="other-designs-section">
                                    <div class="dropdown-toggle" onclick="toggleOtherDesigns({{ $index }})">
                                        <span class="toggle-text">Diğer Öneriler ({{ $otherDesigns->count() }})</span>
                                        <x-heroicon-o-ellipsis-horizontal class="w-6 h-6 toggle-icon other-designs-toggle-{{ $index }}" />
                                    </div>

                                    <div class="other-designs-list" id="other-designs-{{ $index }}" style="display: none;">
                                        @foreach($otherDesigns as $rank => $design)
                                            <div class="other-design-item" onclick="viewDesign({{ $design['id'] }})" title="{{ $rank + 4 }}. sıra">
                                                <div class="other-design-rank">{{ $rank + 4 }}</div>
                                                <div class="other-design-image">
                                                    @if($design['project_image'])
                                                        <img src="{{ $design['project_image'] }}" alt="{{ $design['project_name'] }}">
                                                    @else
                                                        <div class="image-placeholder">
                                                            <x-heroicon-o-building-office class="w-6 h-6" />
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="other-design-info">
                                                    <h5>{{ $design['project_name'] }}</h5>
                                                    <span class="other-design-user">{{ $design['user_name'] }}</span>
                                                    <div class="other-design-stats">
                                                        <span class="likes">
                                                            <x-heroicon-s-heart class="w-4 h-4 text-red-500" />
                                                            {{ $design['likes_count'] }}
                                                        </span>
                                                        <span class="budget">₺{{ number_format($design['project_budget'], 0) }}</span>
                                                    </div>
                                                </div>
                                                <x-heroicon-o-eye class="w-5 h-5 view-icon" />
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Standard Projects Grid -->
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

                                            @if(!$votingEnded)
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
                                            @else
                                                <div class="btn btn-like disabled" title="Oylama süresi doldu">
                                                    <x-heroicon-s-heart class="btn-icon" />
                                                    <span>{{ $design['likes_count'] }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
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

        /* Countdown next to category title */
        .category-countdown {
            display: inline-block;
            margin-left: 0.75rem;
            font-size: 0.875rem;
            color: rgb(var(--gray-600));
            font-weight: 600;
        }

        .category-countdown-inline {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-left: 0.5rem;
        }

        .countdown-lock-icon {
            width: 1rem;
            height: 1rem;
            color: rgb(var(--gray-700));
        }

        .category-countdown {
            font-weight: 700;
            color: rgb(var(--primary-600));
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

        /* Winners Layout Styles - Top Designs Gallery Style */
        .winners-category-section {
            background: rgb(var(--gray-50));
            border: 2px solid rgb(var(--success-400));
            border-radius: 1rem;
            overflow: hidden;
            margin-top: 1rem;
        }

        .dark .winners-category-section {
            background: rgb(var(--gray-800));
            border-color: rgb(var(--success-500));
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 0 15px rgb(var(--success-500) / 0.3);
        }

        .winners-category-section:hover {
            box-shadow: 0 10px 25px -3px rgb(0 0 0 / 0.15), 0 0 25px rgb(var(--success-400) / 0.4);
            transform: translateY(-2px);
        }

        /* Competition Results Header */
        .competition-results-header {
            background: linear-gradient(135deg, rgb(var(--warning-400)) 0%, rgb(var(--warning-600)) 100%);
            border-radius: 1rem;
            padding: 2rem;
            color: white;
            box-shadow: 0 10px 25px -3px rgb(var(--warning-500) / 0.3);
            position: relative;
            overflow: hidden;
            margin: 2rem;
        }

        .competition-results-header::before {
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

        .competition-results-header::after {
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

        .competition-results-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .competition-results-header .header-text {
            flex: 1;
        }

        .competition-results-header h4 {
            font-size: 2rem;
            font-weight: 800;
            margin: 0 0 0.5rem 0;
            text-shadow: 0 2px 4px rgb(0 0 0 / 0.1);
        }

        .competition-results-header p {
            font-size: 1.125rem;
            margin: 0;
            opacity: 0.9;
        }

        .results-badge {
            background: rgb(255 255 255 / 0.2);
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        /* Winners Rankings Grid */
        .winners-rankings-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 1.5rem;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        @media (min-width: 768px) {
            .winners-rankings-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .winners-rankings-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        /* Winner Ranking Cards */
        .winner-ranking-card {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            position: relative;
            transition: all 0.4s ease;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .dark .winner-ranking-card {
            background: rgb(var(--gray-800));
        }

        .winner-ranking-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px -12px rgb(0 0 0 / 0.25);
        }

        /* Winner Effects */
        .winner-ranking-card.winner {
            border: 3px solid rgb(var(--warning-400));
            box-shadow: 0 0 30px rgb(var(--warning-400) / 0.3);
        }

        .winner-ranking-card.winner:hover {
            box-shadow: 0 0 40px rgb(var(--warning-400) / 0.5), 0 20px 40px -12px rgb(0 0 0 / 0.25);
        }

        .winner-ranking-card.second {
            border: 3px solid rgb(var(--gray-400));
            box-shadow: 0 0 20px rgb(var(--gray-400) / 0.2);
        }

        .winner-ranking-card.third {
            border: 3px solid rgb(var(--gray-400));
            box-shadow: 0 0 20px rgb(var(--gray-400) / 0.2);
        }

        /* Design Image for Winners */
        .winner-ranking-card .design-image {
            position: relative;
            height: 14rem;
            background: rgb(var(--gray-100));
            overflow: hidden;
        }

        .dark .winner-ranking-card .design-image {
            background: rgb(var(--gray-700));
        }

        .winner-ranking-card .design-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .winner-ranking-card:hover .design-image img {
            transform: scale(1.1);
        }

        .winner-ranking-card .image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgb(var(--primary-400)), rgb(var(--primary-600)));
        }

        .winner-ranking-card .placeholder-icon {
            width: 4rem;
            height: 4rem;
            color: white;
        }

        /* Likes Badge for Winners */
        .winner-ranking-card .likes-badge {
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

        .winner-ranking-card:hover .likes-badge {
            background: rgb(var(--danger-500));
            transform: scale(1.05);
        }

        .winner-ranking-card .likes-icon {
            width: 1rem;
            height: 1rem;
        }

        /* Design Info for Winners */
        .winner-ranking-card .design-info {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .winner-ranking-card .project-title {
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

        .dark .winner-ranking-card .project-title {
            color: rgb(var(--gray-50));
        }

        .winner-ranking-card .project-user,
        .winner-ranking-card .project-address,
        .winner-ranking-card .project-budget {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
        }

        .winner-ranking-card .info-icon {
            width: 1rem;
            height: 1rem;
            color: rgb(var(--gray-500));
            flex-shrink: 0;
        }

        .dark .winner-ranking-card .info-icon {
            color: rgb(var(--gray-400));
        }

        .winner-ranking-card .project-user span,
        .winner-ranking-card .project-address span {
            color: rgb(var(--gray-600));
            line-clamp: 1;
            -webkit-line-clamp: 1;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .dark .winner-ranking-card .project-user span,
        .dark .winner-ranking-card .project-address span {
            color: rgb(var(--gray-300));
        }

        .winner-ranking-card .budget-info {
            display: flex;
            flex-direction: column;
        }

        .winner-ranking-card .budget-label {
            font-size: 0.75rem;
            color: rgb(var(--gray-500));
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .dark .winner-ranking-card .budget-label {
            color: rgb(var(--gray-400));
        }

        .winner-ranking-card .budget-amount {
            font-weight: 700;
            color: rgb(var(--success-600));
            font-size: 1rem;
        }

        .dark .winner-ranking-card .budget-amount {
            color: rgb(var(--success-400));
        }

        /* Action Button for Winners */
        .winner-ranking-card .design-action {
            margin-top: 0.5rem;
        }

        .winner-ranking-card .btn-view {
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

        .winner-ranking-card .btn-view:hover {
            background: rgb(var(--primary-700));
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgb(var(--primary-600) / 0.3);
        }

        .winner-ranking-card .btn-icon {
            width: 1rem;
            height: 1rem;
        }

        /* Rank Badge Styles */
        .winner-ranking-card .rank-badge {
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

        .winner-ranking-card:hover .rank-badge {
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
            background: linear-gradient(135deg, rgb(var(--gray-400)), rgb(var(--gray-600)));
            color: white;
            box-shadow: 0 4px 15px rgb(var(--gray-500) / 0.4);
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

        /* Animation for winner cards */
        .winner-ranking-card {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards;
        }

        .winner-ranking-card:nth-child(1) { animation-delay: 0.1s; }
        .winner-ranking-card:nth-child(2) { animation-delay: 0.2s; }
        .winner-ranking-card:nth-child(3) { animation-delay: 0.3s; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Sparkle animation for first place */
        .winner-ranking-card.winner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
            pointer-events: none;
        }

        /* Mobile Responsive Styles for Winners */
        @media (max-width: 768px) {
            .competition-results-header {
                margin: 1rem;
                padding: 1.5rem;
            }

            .competition-results-header h4 {
                font-size: 1.75rem;
            }

            .winners-rankings-grid {
                padding: 1rem;
            }
        }

        /* Other Designs Section */
        .other-designs-section {
            background: white;
            border: 1px solid rgb(var(--gray-200));
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .dark .other-designs-section {
            background: rgb(var(--gray-800));
            border-color: rgb(var(--gray-700));
        }

        .dropdown-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            cursor: pointer;
            transition: all 0.2s;
            background: rgb(var(--gray-50));
        }

        .dark .dropdown-toggle {
            background: rgb(var(--gray-700) / 0.5);
        }

        .dropdown-toggle:hover {
            background: rgb(var(--gray-100));
        }

        .dark .dropdown-toggle:hover {
            background: rgb(var(--gray-700));
        }

        .toggle-text {
            font-weight: 600;
            color: rgb(var(--gray-900));
        }

        .dark .toggle-text {
            color: rgb(var(--gray-50));
        }

        .toggle-icon {
            color: rgb(var(--gray-500));
            transition: transform 0.2s;
        }

        .dark .toggle-icon {
            color: rgb(var(--gray-400));
        }

        .dropdown-toggle.active .toggle-icon {
            transform: rotate(180deg);
        }

        /* Other Designs List */
        .other-designs-list {
            max-height: 400px;
            overflow-y: auto;
            border-top: 1px solid rgb(var(--gray-200));
        }

        .dark .other-designs-list {
            border-top-color: rgb(var(--gray-700));
        }

        .other-design-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgb(var(--gray-100));
            cursor: pointer;
            transition: all 0.2s;
        }

        .dark .other-design-item {
            border-bottom-color: rgb(var(--gray-700) / 0.5);
        }

        .other-design-item:last-child {
            border-bottom: none;
        }

        .other-design-item:hover {
            background: rgb(var(--gray-50));
        }

        .dark .other-design-item:hover {
            background: rgb(var(--gray-700) / 0.3);
        }

        .other-design-rank {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            background: rgb(var(--gray-200));
            color: rgb(var(--gray-700));
            border-radius: 50%;
            font-weight: 700;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        .dark .other-design-rank {
            background: rgb(var(--gray-700));
            color: rgb(var(--gray-300));
        }

        .other-design-image {
            width: 3rem;
            height: 3rem;
            border-radius: 0.5rem;
            overflow: hidden;
            background: rgb(var(--gray-100));
            flex-shrink: 0;
        }

        .dark .other-design-image {
            background: rgb(var(--gray-700));
        }

        .other-design-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .other-design-image .image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgb(var(--primary-400)), rgb(var(--primary-600)));
            color: white;
        }

        .other-design-info {
            flex: 1;
            min-width: 0;
        }

        .other-design-info h5 {
            font-size: 0.875rem;
            font-weight: 600;
            color: rgb(var(--gray-900));
            margin: 0 0 0.25rem 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dark .other-design-info h5 {
            color: rgb(var(--gray-50));
        }

        .other-design-user {
            font-size: 0.75rem;
            color: rgb(var(--gray-500));
            margin-bottom: 0.5rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dark .other-design-user {
            color: rgb(var(--gray-400));
        }

        .other-design-stats {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.75rem;
        }

        .other-design-stats .likes {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            color: rgb(var(--gray-600));
        }

        .dark .other-design-stats .likes {
            color: rgb(var(--gray-300));
        }

        .other-design-stats .budget {
            font-weight: 600;
            color: rgb(var(--success-600));
        }

        .dark .other-design-stats .budget {
            color: rgb(var(--success-400));
        }

        .view-icon {
            color: rgb(var(--gray-400));
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .dark .view-icon {
            color: rgb(var(--gray-500));
        }

        .other-design-item:hover .view-icon {
            color: rgb(var(--primary-500));
            transform: scale(1.1);
        }

        /* Disabled Like Button */
        .btn-like.disabled {
            opacity: 0.6;
            cursor: not-allowed;
            background: rgb(var(--gray-100)) !important;
            color: rgb(var(--gray-400)) !important;
        }

        .dark .btn-like.disabled {
            background: rgb(var(--gray-700)) !important;
            color: rgb(var(--gray-500)) !important;
        }

        /* Animation for winner cards */
        .winner-card {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s ease forwards;
        }

        .first-place {
            animation-delay: 0.2s;
        }

        .second-place {
            animation-delay: 0.1s;
        }

        .third-place {
            animation-delay: 0.3s;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Sparkle animation for first place */
        .first-place::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 20%, rgb(var(--warning-300) / 0.3) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgb(var(--warning-300) / 0.3) 0%, transparent 50%),
                        radial-gradient(circle at 40% 70%, rgb(var(--warning-300) / 0.3) 0%, transparent 50%);
            pointer-events: none;
            animation: sparkle 3s ease-in-out infinite;
            border-radius: 1rem;
        }

        @keyframes sparkle {
            0%, 100% { opacity: 0; }
            50% { opacity: 1; }
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

        // Toggle other designs dropdown
        function toggleOtherDesigns(index) {
            const dropdown = document.querySelector(`#other-designs-${index}`);
            const toggleButton = dropdown?.previousElementSibling;
            const toggleIcon = document.querySelector(`.other-designs-toggle-${index}`);

            if (!dropdown || !toggleButton) return;

            const isVisible = dropdown.style.display !== 'none';

            if (isVisible) {
                dropdown.style.display = 'none';
                toggleButton.classList.remove('active');
                if (toggleIcon) {
                    toggleIcon.style.transform = 'rotate(0deg)';
                }
            } else {
                dropdown.style.display = 'block';
                toggleButton.classList.add('active');
                if (toggleIcon) {
                    toggleIcon.style.transform = 'rotate(180deg)';
                }
            }
        }

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            const gallery = new ProjectGallery();
            initCategoryCountdowns();
        });

        // Countdown helpers
        function formatCountdown(diffMs) {
            if (diffMs <= 0) return '{{ __("app.time_expired") }}';

            const totalSeconds = Math.floor(diffMs / 1000);
            const totalMinutes = Math.floor(totalSeconds / 60);
            const totalHours = Math.floor(totalMinutes / 60);
            const totalDays = Math.floor(totalHours / 24);

            // Exact calculations
            const seconds = totalSeconds % 60;
            const minutes = totalMinutes % 60;
            const hours = totalHours % 24;

            // For periods longer than 7 days, show weeks and days
            if (totalDays >= 7) {
                const weeks = Math.floor(totalDays / 7);
                const remainingDays = totalDays % 7;
                if (remainingDays > 0) {
                    return `${weeks} hafta ${remainingDays} gün`;
                } else {
                    return `${weeks} hafta`;
                }
            }

            // For periods 1-6 days, show days and hours
            if (totalDays >= 1) {
                if (hours > 0) {
                    return `${totalDays} gün ${hours} saat`;
                } else {
                    return `${totalDays} gün`;
                }
            }

            // For periods less than 1 day, show hours, minutes, seconds
            if (totalHours >= 1) {
                if (minutes > 0) {
                    return `${totalHours} saat ${minutes} dk`;
                } else {
                    return `${totalHours} saat`;
                }
            }

            // For periods less than 1 hour, show minutes and seconds
            if (totalMinutes >= 1) {
                if (seconds > 0) {
                    return `${totalMinutes} dk ${seconds} sn`;
                } else {
                    return `${totalMinutes} dk`;
                }
            }

            // For periods less than 1 minute, show only seconds
            return `${totalSeconds} sn`;
        }

        function initCategoryCountdowns() {
            const elements = Array.from(document.querySelectorAll('.category-countdown'));
            if (!elements.length) return;

            // Parse data and store targets
            const items = elements.map(el => {
                const startStr = el.dataset.start;
                const endStr = el.dataset.end;

                const start = startStr ? new Date(startStr) : null;
                const end = endStr ? new Date(endStr) : null;

                // Debug: Log parsed dates and current time
                const now = new Date();
                if (start || end) {
                    console.log('Countdown element:', {
                        startStr,
                        endStr,
                        start: start ? start.toLocaleString('tr-TR') : null,
                        end: end ? end.toLocaleString('tr-TR') : null,
                        now: now.toLocaleString('tr-TR'),
                        nowUTC: now.toISOString(),
                        endUTC: end ? end.toISOString() : null
                    });

                    if (end) {
                        const diffMs = end.getTime() - now.getTime();
                        console.log('Time calculation:', {
                            endTime: end.getTime(),
                            nowTime: now.getTime(),
                            diffMs: diffMs,
                            diffMinutes: Math.floor(diffMs / (1000 * 60)),
                            formatted: formatCountdown(diffMs)
                        });
                    }
                }

                // Use end if available, otherwise use start as a target
                const target = end || start;
                return { el, start, end, target };
            }).filter(i => i.target && !isNaN(i.target.getTime())); // Filter out invalid dates

            if (!items.length) return;

            function tick() {
                const now = new Date();

                items.forEach(item => {
                    // Prefer countdown to the vote end if available
                    if (item.end && !isNaN(item.end.getTime())) {
                        const diffEnd = item.end.getTime() - now.getTime();

                        if (diffEnd <= 0) {
                            item.el.textContent = 'Oylama süresi doldu';
                            item.el.style.color = 'rgb(239 68 68)'; // red-500

                            // Debug log for expired voting
                            console.log('Voting expired for element:', {
                                element: item.el,
                                endTime: item.end,
                                currentTime: now,
                                diffMs: diffEnd,
                                categoryIndex: item.el.id?.split('-')[1] || 'unknown'
                            });

                            // Check if we need to trigger any actions when voting ends
                            // This could be where we trigger a page refresh or Livewire update
                            if (!item.el.dataset.expired) {
                                item.el.dataset.expired = 'true';
                                console.log('First time marking as expired, might need page refresh');

                                // Optional: Trigger page refresh after voting ends
                                // setTimeout(() => {
                                //     window.location.reload();
                                // }, 2000);
                            }
                        } else {
                            item.el.textContent = 'Oylama bitimine: ' + formatCountdown(diffEnd);
                            item.el.style.color = 'rgb(var(--primary-600))';
                        }
                        return;
                    }

                    // If no end date, but start exists and is in future, show time to start
                    if (item.start && !isNaN(item.start.getTime())) {
                        const diffStart = item.start.getTime() - now.getTime();

                        if (diffStart > 0) {
                            item.el.textContent = 'Başlama: ' + formatCountdown(diffStart);
                            item.el.style.color = 'rgb(var(--warning-600))';
                        } else {
                            // Started but no end date specified
                            item.el.textContent = 'Devam ediyor';
                            item.el.style.color = 'rgb(var(--success-600))';
                        }
                        return;
                    }

                    // Fallback
                    item.el.textContent = 'Zaman bilgisi mevcut değil';
                    item.el.style.color = 'rgb(var(--gray-500))';
                });
            }

            // Run immediately then every second for fine-grained updates
            tick();
            const intervalId = setInterval(tick, 1000);

            // Store interval so it can be cleared if needed
            if (document._categoryCountdownInterval) {
                clearInterval(document._categoryCountdownInterval);
            }
            document._categoryCountdownInterval = intervalId;
        }
    </script>
</x-filament-panels::page>
