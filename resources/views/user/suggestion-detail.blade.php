@extends('user.layout')

@section('title', $suggestion->title . ' - DUT Vote')

@section('content')
<!-- Header Section with Background -->
<section class="section-padding dynamic-background {{ ($hasBackgroundImages ?? false) ? '' : 'no-background-images' }}" style="padding: 2rem 0;">
    @if($hasBackgroundImages ?? false)
        @if($randomBackgroundImage)
            <!-- Single Random Background Image -->
            <div class="background-image-container">
                <img src="{{ $randomBackgroundImage }}"
                     alt="Öneri Detayı"
                     class="background-image-main"
                     loading="eager">
            </div>
        @endif
        <div class="background-image-overlay"></div>
    @endif

    <div class="user-container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb" style="position: relative; z-index: 3;">
            <a href="{{ route('user.index') }}">Ana Sayfa</a>
            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
            <a href="{{ route('user.projects') }}">Projeler</a>
            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
            <span style="color: var(--gray-900);">{{ Str::limit($suggestion->title, 50) }}</span>
        </nav>
    </div>
</section>

<div class="detail-container">
    <div class="user-container">

        <div class="detail-card">
            <!-- Header -->
            <div class="detail-header">
                <div class="d-flex align-start justify-between content-spacing-lg">
                    <div style="flex: 1;">
                        <h1 class="detail-title">{{ $suggestion->title }}</h1>

                        <!-- Project Info -->
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                            <span style="font-size: 0.875rem; color: var(--gray-500);">Proje:</span>
                            <span style="display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: var(--radius-xl); font-size: 0.875rem; background: var(--green-100); color: var(--green-800);">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                                </svg>
                                {{ $suggestion->category->name }}
                            </span>
                        </div>

                        <!-- Meta Information -->
                        <div class="detail-meta">
                            <div style="display: flex; align-items: center;">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                </svg>
                                {{ $suggestion->createdBy ? $suggestion->createdBy->name : 'Anonim' }}
                            </div>

                            @if($suggestion->budget)
                            <div style="display: flex; align-items: center;">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem; color: var(--green-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H4.5m2.25 0v3m0 0v.375c0 .621.504 1.125 1.125 1.125H9M3.75 4.5h.375c.621 0 1.125.504 1.125 1.125v.75c0 .621-.504 1.125-1.125 1.125H3.75V4.5ZM11.25 4.5h.375c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125H11.25V4.5ZM16.5 4.5h.375c.621 0 1.125.504 1.125 1.125v3c0 .621-.504 1.125-1.125 1.125H16.5V4.5Z"/>
                                </svg>
                                <span style="font-weight: 600; color: var(--green-700);">{{ number_format($suggestion->budget, 0) }} ₺</span>
                            </div>
                            @endif

                            @if($suggestion->estimated_duration)
                            <div style="display: flex; align-items: center;">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                                {{ $suggestion->estimated_duration }} gün
                            </div>
                            @endif

                            <div style="display: flex; align-items: center;">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                </svg>
                                {{ $suggestion->created_at->format('d.m.Y') }}
                            </div>
                        </div>
                    </div>

                    <!-- Like Button (Large) - Radio Button Logic -->
                    <div style="flex-shrink: 0; margin-left: 1.5rem;">
                        <button onclick="toggleLike({{ $suggestion->id }})"
                                class="btn-like {{ Auth::check() && $suggestion->likes->where('user_id', Auth::id())->count() > 0 ? 'liked' : '' }}"
                                style="display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; font-size: 1rem; font-weight: 500; position: relative;"
                                data-suggestion-id="{{ $suggestion->id }}"
                                data-category="{{ $suggestion->category_id ?? 'default' }}"
                                title="Bu kategoride sadece bir öneri beğenilebilir (Radio buton mantığı)">

                            @php
                                $userHasLikedInProject = false;
                                $userLikedSuggestionInProject = null;
                                if (Auth::check()) {
                                    $projectSuggestions = \App\Models\Oneri::where('category_id', $suggestion->category_id)->get();
                                    foreach($projectSuggestions as $projectSuggestion) {
                                        if ($projectSuggestion->likes->where('user_id', Auth::id())->count() > 0) {
                                            $userHasLikedInProject = true;
                                            $userLikedSuggestionInProject = $projectSuggestion->id;
                                            break;
                                        }
                                    }
                                }
                            @endphp

                            <!-- Radio button indicator -->
                            <div style="width: 1rem; height: 1rem; border: 2px solid currentColor; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 0.25rem; opacity: 0.7;">
                                @if($userLikedSuggestionInProject == $suggestion->id)
                                <div style="width: 0.5rem; height: 0.5rem; background: currentColor; border-radius: 50%;"></div>
                                @endif
                            </div>

                            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                            </svg>
                            <span class="like-count">{{ $suggestion->likes->count() }}</span>
                            <span>Beğeni</span>

                            <!-- Show selection indicator if user has liked this suggestion -->
                            @if($userHasLikedInProject && $userLikedSuggestionInProject == $suggestion->id)
                            <span style="margin-left: 0.5rem; font-size: 0.875rem; opacity: 0.8;">✓ Seçili</span>
                            @endif
                        </button>

                        <!-- Radio button explanation -->
                        @if($userHasLikedInProject)
                        <div style="margin-top: 0.5rem; font-size: 0.75rem; color: var(--gray-600); text-align: center; line-height: 1.3;">
                            <svg style="width: 0.75rem; height: 0.75rem; display: inline; margin-right: 0.25rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                            </svg>
                            Bu projede bir seçiminiz var
                        </div>
                        @else
                        <div style="margin-top: 0.5rem; font-size: 0.75rem; color: var(--gray-600); text-align: center; line-height: 1.3;">
                            <svg style="width: 0.75rem; height: 0.75rem; display: inline; margin-right: 0.25rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                            </svg>
                            Proje başına bir öneri beğenilebilir
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Location Info -->
                @if($suggestion->address || $suggestion->district)
                <div class="detail-location">
                    <div style="display: flex; align-items: start; gap: 0.5rem;">
                        <svg style="width: 1.25rem; height: 1.25rem; color: var(--gray-400); margin-top: 0.125rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                        </svg>
                        <div>
                            <h4 style="font-weight: 500; color: var(--gray-900); margin-bottom: 0.25rem;">Konum</h4>
                            <p style="font-size: 0.875rem; color: var(--gray-600);">
                                @if($suggestion->district)
                                    {{ $suggestion->district }}
                                    @if($suggestion->neighborhood)
                                        , {{ $suggestion->neighborhood }}
                                    @endif
                                @endif
                                @if($suggestion->address)
                                    <br>{{ $suggestion->address }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Main Content -->
            <div class="d-grid" style="grid-template-columns: 2fr 1fr; gap: 2rem;">
                <!-- Content Area -->
                <div class="detail-content">
                    <!-- Suggestion Image -->
                    @if($suggestion->getFirstMediaUrl('images'))
                    <div class="content-spacing">
                        <img src="{{ $suggestion->getFirstMediaUrl('images') }}"
                             alt="{{ $suggestion->title }}"
                             style="width: 100%; height: 16rem; object-fit: cover; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);"
                             onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'; this.style.display='none'; this.parentElement.innerHTML='<div style=&quot;width: 100%; height: 16rem; background: linear-gradient(135deg, var(--green-100) 0%, var(--green-200) 100%); display: flex; align-items: center; justify-content: center; border-radius: var(--radius-lg);&quot;><div style=&quot;text-align: center;&quot;><svg style=&quot;width: 4rem; height: 4rem; color: var(--green-600); margin-bottom: 0.5rem;&quot; fill=&quot;currentColor&quot; viewBox=&quot;0 0 20 20&quot;><path fill-rule=&quot;evenodd&quot; d=&quot;M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z&quot; clip-rule=&quot;evenodd&quot;/></svg><p style=&quot;color: var(--green-700); font-size: 1rem;&quot;>Öneri Görseli</p></div></div>';">
                    </div>
                    @endif

                    <!-- Description -->
                    <div class="content-spacing">
                        <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--gray-900); margin-bottom: 1rem;">Açıklama</h3>
                        <div style="color: var(--gray-600); font-size: 1rem; line-height: 1.7;">
                            {!! nl2br(e($suggestion->description)) !!}
                        </div>
                    </div>

                    <!-- Comments Section -->
                    @if($suggestion->approvedComments->count() > 0)
                    <div style="border-top: 1px solid var(--green-100); padding-top: 2rem;">
                        <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--gray-900); margin-bottom: 1.5rem;">
                            Yorumlar ({{ $suggestion->approvedComments->count() }})
                        </h3>

                        <div class="d-flex" style="flex-direction: column; gap: 1.5rem;">
                            @foreach($suggestion->approvedComments as $comment)
                            <div class="comment-card">
                                <div class="comment-header">
                                    <div class="comment-avatar">
                                        {{ substr($comment->user->name ?? 'A', 0, 1) }}
                                    </div>
                                    <div>
                                        <h4 class="comment-author">{{ $comment->user->name ?? 'Anonim' }}</h4>
                                        <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <p class="comment-content">{{ $comment->comment }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="detail-sidebar">
                    <!-- Stats -->
                    <div class="content-spacing">
                        <h4 style="font-weight: 600; color: var(--gray-900); margin-bottom: 1rem;">İstatistikler</h4>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <span class="stat-label">Toplam Beğeni</span>
                                <span class="stat-value">{{ $suggestion->likes->count() }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Toplam Yorum</span>
                                <span class="stat-value">{{ $suggestion->approvedComments->count() }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Oluşturma Tarihi</span>
                                <span class="stat-value">{{ $suggestion->created_at->format('d.m.Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Likes -->
                    @if($suggestion->likes->count() > 0)
                    <div class="content-spacing">
                        <h4 style="font-weight: 600; color: var(--gray-900); margin-bottom: 1rem;">Son Beğenenler</h4>
                        <div class="d-flex" style="flex-direction: column; gap: 0.5rem;">
                            @foreach($suggestion->likes->take(5) as $like)
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                                <div style="width: 1.5rem; height: 1.5rem; background: var(--green-300); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <span style="font-size: 0.75rem; font-weight: 500; color: var(--green-800);">
                                        {{ substr($like->user->name ?? 'A', 0, 1) }}
                                    </span>
                                </div>
                                <span style="color: var(--gray-600);">{{ $like->user->name ?? 'Anonim' }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="d-flex" style="flex-direction: column; gap: 0.75rem;">
                        <a href="{{ route('user.projects') }}#project-{{ $suggestion->category_id }}"
                           class="btn btn-secondary" style="justify-content: center;">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/>
                            </svg>
                            Projeye Dön
                        </a>

                        <a href="{{ route('user.projects') }}"
                           class="btn btn-primary" style="justify-content: center;">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z"/>
                            </svg>
                            Tüm Projeler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle like with AJAX (Radio button logic: one per category)
function toggleLike(suggestionId) {
    @guest
        showMessage('Beğeni yapmak için giriş yapmanız gerekiyor.', 'error');
        setTimeout(() => {
            window.location.href = '{{ route('user.login') }}';
        }, 2000);
        return;
    @endguest

    const clickedButton = document.querySelector(`[data-suggestion-id="${suggestionId}"]`);
    const likeCount = clickedButton.querySelector('.like-count');

    // Get current suggestion's category from the data attributes
    const suggestionCategory = clickedButton.getAttribute('data-category') || 'default';

    // Find all buttons in the same category (radio button behavior)
    const allButtonsInCategory = document.querySelectorAll(`[data-category="${suggestionCategory}"]`);

    // Disable all buttons in this category during request
    allButtonsInCategory.forEach(btn => {
        btn.disabled = true;
        btn.style.opacity = '0.7';
        btn.style.pointerEvents = 'none';
    });

    $.ajax({
        url: `/suggestions/${suggestionId}/toggle-like`,
        method: 'POST',
        data: {
            category: suggestionCategory
        },
        success: function(response) {
            // Reset all buttons in the same category to default state (radio button logic)
            allButtonsInCategory.forEach(btn => {
                btn.classList.remove('liked');

                // Reset heart icon if exists
                const heartIcon = btn.querySelector('.like-icon');
                if (heartIcon) {
                    heartIcon.style.fill = 'none';
                }

                // Update like counts if response contains data for other buttons in category
                const btnSuggestionId = btn.getAttribute('data-suggestion-id');
                if (response.all_likes && response.all_likes[btnSuggestionId] !== undefined) {
                    const btnLikeCount = btn.querySelector('.like-count');
                    if (btnLikeCount) {
                        btnLikeCount.textContent = response.all_likes[btnSuggestionId];
                    }
                }
            });

            // Update clicked button's like count
            likeCount.textContent = response.likes_count;

            if (response.liked) {
                clickedButton.classList.add('liked');

                // Fill heart icon for the selected button
                const heartIcon = clickedButton.querySelector('.like-icon');
                if (heartIcon) {
                    heartIcon.style.fill = 'currentColor';
                }

                if (response.switched_from) {
                    showMessage(`✓ Seçiminiz "${response.switched_from}" önerisinden "${response.current_title}" önerisine değiştirildi.`, 'success');
                } else {
                    showMessage('✓ Öneri beğenildi! Bu kategoride sadece bir öneri beğenilebilir.', 'success');
                }
            } else {
                clickedButton.classList.remove('liked');

                // Reset heart icon to outline
                const heartIcon = clickedButton.querySelector('.like-icon');
                if (heartIcon) {
                    heartIcon.style.fill = 'none';
                }

                showMessage('Beğeni kaldırıldı.', 'info');
            }
        },
        error: function(xhr) {
            let message = 'Bir hata oluştu.';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                message = xhr.responseJSON.error;
            }
            showMessage(message, 'error');
        },
        complete: function() {
            // Re-enable all buttons in the category
            allButtonsInCategory.forEach(btn => {
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.style.pointerEvents = 'auto';
            });
        }
    });
}

function showMessage(message, type = 'info') {
    // Remove any existing messages first
    const existingMessages = document.querySelectorAll('.message');
    existingMessages.forEach(msg => msg.remove());

    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}`;

    // Add appropriate icon based on message type
    const icons = {
        success: '✓',
        error: '✗',
        info: 'ℹ'
    };

    const icon = icons[type] || 'ℹ';
    messageDiv.innerHTML = `<span style="margin-right: 0.5rem; font-weight: bold;">${icon}</span>${message}`;

    // Position it better for mobile
    messageDiv.style.cssText = `
        position: fixed;
        top: 1rem;
        right: 1rem;
        left: 1rem;
        max-width: 400px;
        margin: 0 auto;
        padding: 1rem 1.5rem;
        border-radius: var(--radius-lg);
        color: white;
        font-weight: 500;
        z-index: 1000;
        animation: slideIn 0.3s ease;
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        backdrop-filter: blur(8px);
    `;

    // Apply type-specific styling
    switch(type) {
        case 'success':
            messageDiv.style.background = 'linear-gradient(135deg, var(--green-600) 0%, var(--green-700) 100%)';
            break;
        case 'error':
            messageDiv.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
            break;
        case 'info':
            messageDiv.style.background = 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)';
            break;
    }

    document.body.appendChild(messageDiv);

    // Auto remove after delay based on message length
    const delay = Math.max(3000, message.length * 50);
    setTimeout(() => {
        messageDiv.style.animation = 'slideOut 0.3s ease forwards';
        setTimeout(() => messageDiv.remove(), 300);
    }, delay);
}

// Add CSS for message animations
if (!document.getElementById('message-styles')) {
    const style = document.createElement('style');
    style.id = 'message-styles';
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateY(-100%) translateX(-50%);
                opacity: 0;
            }
            to {
                transform: translateY(0) translateX(-50%);
                opacity: 1;
            }
        }
        @keyframes slideOut {
            from {
                transform: translateY(0) translateX(-50%);
                opacity: 1;
            }
            to {
                transform: translateY(-100%) translateX(-50%);
                opacity: 0;
            }
        }
        .message {
            transform: translateX(-50%);
        }
        @media (min-width: 640px) {
            .message {
                right: 1rem !important;
                left: auto !important;
                max-width: 400px !important;
                transform: none !important;
            }
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        }
    `;
    document.head.appendChild(style);
}

// Responsive layout adjustment
function adjustDetailLayout() {
    const detailGrid = document.querySelector('[style*="grid-template-columns: 2fr 1fr"]');
    if (detailGrid && window.innerWidth < 1024) {
        detailGrid.style.gridTemplateColumns = '1fr';
        detailGrid.style.gap = '1rem';
    } else if (detailGrid) {
        detailGrid.style.gridTemplateColumns = '2fr 1fr';
        detailGrid.style.gap = '2rem';
    }
}

// Call on load and resize
window.addEventListener('load', adjustDetailLayout);
window.addEventListener('resize', adjustDetailLayout);
</script>
    </div>
</div>
@endsection
