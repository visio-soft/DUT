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
            <svg style="width: 1rem; height: 1rem;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <a href="{{ route('user.projects') }}">Projeler</a>
            <svg style="width: 1rem; height: 1rem;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
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
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                </svg>
                                {{ $suggestion->category->name }}
                            </span>
                        </div>

                        <!-- Meta Information -->
                        <div class="detail-meta">
                            <div style="display: flex; align-items: center;">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                </svg>
                                {{ $suggestion->createdBy ? $suggestion->createdBy->name : 'Anonim' }}
                            </div>

                            @if($suggestion->budget)
                            <div style="display: flex; align-items: center;">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem; color: var(--green-600);" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                                <span style="font-weight: 600; color: var(--green-700);">{{ number_format($suggestion->budget, 0) }} ₺</span>
                            </div>
                            @endif

                            @if($suggestion->estimated_duration)
                            <div style="display: flex; align-items: center;">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                {{ $suggestion->estimated_duration }} gün
                            </div>
                            @endif

                            <div style="display: flex; align-items: center;">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"/>
                                </svg>
                                {{ $suggestion->created_at->format('d.m.Y') }}
                            </div>
                        </div>
                    </div>

                    <!-- Like Button (Large) -->
                    <div style="flex-shrink: 0; margin-left: 1.5rem;">
                        <button onclick="toggleLike({{ $suggestion->id }})"
                                class="btn-like {{ Auth::check() && $suggestion->likes->where('user_id', Auth::id())->count() > 0 ? 'liked' : '' }}"
                                style="display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; font-size: 1rem; font-weight: 500;"
                                data-suggestion-id="{{ $suggestion->id }}">
                            <svg style="width: 1.25rem; height: 1.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                            </svg>
                            <span class="like-count">{{ $suggestion->likes->count() }}</span>
                            <span>Beğeni</span>
                        </button>
                    </div>
                </div>

                <!-- Location Info -->
                @if($suggestion->address || $suggestion->district)
                <div class="detail-location">
                    <div style="display: flex; align-items: start; gap: 0.5rem;">
                        <svg style="width: 1.25rem; height: 1.25rem; color: var(--gray-400); margin-top: 0.125rem;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
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
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0L2.586 11l3.707-3.707a1 1 0 011.414 1.414L5.414 11l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                            </svg>
                            Projeye Dön
                        </a>

                        <a href="{{ route('user.projects') }}"
                           class="btn btn-primary" style="justify-content: center;">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
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
// Toggle like with AJAX (same as projects page)
function toggleLike(suggestionId) {
    @guest
        showMessage('Beğeni yapmak için giriş yapmanız gerekiyor.', 'error');
        setTimeout(() => {
            window.location.href = '/admin/login';
        }, 2000);
        return;
    @endguest

    const button = document.querySelector(`[data-suggestion-id="${suggestionId}"]`);
    const likeCount = button.querySelector('.like-count');

    button.disabled = true;
    button.classList.add('loading');

    $.ajax({
        url: `/suggestions/${suggestionId}/toggle-like`,
        method: 'POST',
        success: function(response) {
            likeCount.textContent = response.likes_count;

            if (response.liked) {
                button.classList.add('liked');
            } else {
                button.classList.remove('liked');
            }

            showMessage(response.message, 'success');
        },
        error: function(xhr) {
            let message = 'Bir hata oluştu.';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                message = xhr.responseJSON.error;
            }
            showMessage(message, 'error');
        },
        complete: function() {
            button.disabled = false;
            button.classList.remove('loading');
        }
    });
}

function showMessage(message, type) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}`;
    messageDiv.textContent = message;

    document.body.appendChild(messageDiv);

    setTimeout(() => {
        messageDiv.remove();
    }, 3000);
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
