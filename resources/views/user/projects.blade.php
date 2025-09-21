@extends('user.layout')

@section('title', 'Projeler - DUT Vote')

@section('content')
<!-- Header Section with Background -->
<section class="section-padding dynamic-background {{ ($hasBackgroundImages ?? false) ? '' : 'no-background-images' }}" style="padding: 3rem 0;">
    @if($hasBackgroundImages ?? false)
        @if($randomBackgroundImage)
            <!-- Single Random Background Image -->
            <div class="background-image-container">
                <img src="{{ $randomBackgroundImage }}"
                     alt="Projeler"
                     class="background-image-main"
                     loading="eager">
            </div>
        @endif
        <div class="background-image-overlay"></div>
    @endif

    <div class="user-container">
        <!-- Page Header -->
        <div class="text-center content-spacing-xl" style="position: relative; z-index: 3;">
            <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--gray-900); margin-bottom: 1rem; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Tüm Projeler</h1>
            <p style="font-size: 1.125rem; color: var(--gray-700); max-width: 600px; margin: 0 auto; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                Şehrimizi güzelleştirmek için hazırlanan projeler ve yaratıcı öneriler
            </p>
        </div>
    </div>
</section>

<div class="section-padding">
    <div class="user-container">

        @if($projects->count() > 0)
        <div class="d-grid" style="grid-template-columns: 1fr 3fr; gap: 2rem;">
            <!-- Sol Taraf: Tree View -->
            <div>
                <div class="tree-view">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-900); margin-bottom: 1rem;">Proje Listesi</h3>
                    <div style="space-y: 0.5rem;">
                        @foreach($projects as $project)
                        <div style="border-bottom: 1px solid var(--green-100); padding-bottom: 0.5rem;">
                            <!-- Project Node -->
                            <div class="tree-project"
                                 data-project-id="{{ $project->id }}"
                                 onclick="scrollToProject({{ $project->id }})">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem; color: var(--green-600);" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                </svg>
                                <span style="font-size: 0.875rem; font-weight: 500; color: var(--gray-900); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ Str::limit($project->name, 25) }}</span>
                                <span style="margin-left: auto; font-size: 0.75rem; color: var(--gray-500);">({{ $project->oneriler->count() }})</span>
                            </div>

                            <!-- Suggestions -->
                            @if($project->oneriler->count() > 0)
                            <div class="tree-suggestions">
                                @foreach($project->oneriler as $suggestion)
                                <div class="tree-suggestion"
                                     onclick="scrollToSuggestion({{ $suggestion->id }})">
                                    <svg style="width: 0.75rem; height: 0.75rem; margin-right: 0.5rem; color: var(--green-500);" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ Str::limit($suggestion->title, 20) }}</span>
                                    <span style="margin-left: auto; display: flex; align-items: center;">
                                        <svg style="width: 0.75rem; height: 0.75rem; color: var(--green-600); margin-right: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                                        </svg>
                                        {{ $suggestion->likes->count() }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sağ Taraf: Project Cards -->
            <div>
                <div class="d-flex" style="flex-direction: column; gap: 2rem;">
                    @foreach($projects as $project)
                    <div id="project-{{ $project->id }}" class="user-card">
                        <!-- Project Header -->
                        <div class="user-card-header">
                            <div class="d-flex align-start justify-between">
                                <div style="flex: 1;">
                                    <h2 class="user-card-title" style="font-size: 1.5rem; color: var(--green-800);">{{ $project->name }}</h2>
                                    <p class="user-card-description">
                                        {{ $project->description }}
                                    </p>

                                    <!-- Project Meta -->
                                    <div class="user-card-meta">
                                        <div class="project-stat">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $project->oneriler->count() }} Öneri
                                        </div>

                                        @if($project->district)
                                        <div class="project-stat">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $project->district }}, {{ $project->neighborhood }}
                                        </div>
                                        @endif

                                        @if($project->start_datetime)
                                        <div class="project-stat">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"/>
                                            </svg>
                                            {{ $project->start_datetime->format('d.m.Y') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Project Image -->
                                @if($project->getFirstMediaUrl('project_files'))
                                <div style="margin-left: 1.5rem; flex-shrink: 0;">
                                    <div style="width: 8rem; height: 6rem; background: var(--gray-200); border-radius: var(--radius-lg); overflow: hidden;">
                                        <img src="{{ $project->getFirstMediaUrl('project_files') }}"
                                             alt="{{ $project->name }}"
                                             style="width: 100%; height: 100%; object-fit: cover;"
                                             onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'; this.style.display='none'; this.parentElement.innerHTML='<div style=&quot;width: 100%; height: 100%; background: linear-gradient(135deg, var(--green-100) 0%, var(--green-200) 100%); display: flex; align-items: center; justify-content: center;&quot;><svg style=&quot;width: 2rem; height: 2rem; color: var(--green-600);&quot; fill=&quot;currentColor&quot; viewBox=&quot;0 0 20 20&quot;><path fill-rule=&quot;evenodd&quot; d=&quot;M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z&quot; clip-rule=&quot;evenodd&quot;/></svg></div>';">
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Suggestions -->
                        @if($project->oneriler->count() > 0)
                        <div class="user-card-content">
                            <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-900); margin-bottom: 1rem;">Öneriler ({{ $project->oneriler->count() }})</h3>
                            <div class="suggestion-grid">
                                @foreach($project->oneriler as $suggestion)
                                <div id="suggestion-{{ $suggestion->id }}" class="suggestion-card">
                                    <div class="suggestion-content">
                                        <!-- Suggestion Image -->
                                        <div class="suggestion-image">
                                            @if($suggestion->getFirstMediaUrl('images'))
                                                <img src="{{ $suggestion->getFirstMediaUrl('images') }}"
                                                     alt="{{ $suggestion->title }}"
                                                     onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'; this.style.display='none'; this.parentElement.innerHTML='<div style=&quot;width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: var(--gray-200);&quot;><svg style=&quot;width: 1.5rem; height: 1.5rem; color: var(--gray-400);&quot; fill=&quot;currentColor&quot; viewBox=&quot;0 0 20 20&quot;><path fill-rule=&quot;evenodd&quot; d=&quot;M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z&quot; clip-rule=&quot;evenodd&quot;/></svg></div>';">
                                            @else
                                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                                    <svg style="width: 1.5rem; height: 1.5rem; color: var(--gray-400);" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Suggestion Content -->
                                        <div class="suggestion-info">
                                            <h4 class="suggestion-title">{{ $suggestion->title }}</h4>
                                            <p class="suggestion-description">{{ Str::limit($suggestion->description, 100) }}</p>

                                            <!-- Suggestion Meta -->
                                            @if($suggestion->budget)
                                            <div class="suggestion-budget">
                                                💰 {{ number_format($suggestion->budget, 0) }} ₺
                                            </div>
                                            @endif

                                            <!-- Action Buttons -->
                                            <div class="suggestion-actions">
                                                <button onclick="toggleLike({{ $suggestion->id }})"
                                                        class="btn-like {{ Auth::check() && $suggestion->likes->where('user_id', Auth::id())->count() > 0 ? 'liked' : '' }}"
                                                        data-suggestion-id="{{ $suggestion->id }}">
                                                    <svg style="width: 1rem; height: 1rem;" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                                                    </svg>
                                                    <span class="like-count">{{ $suggestion->likes->count() }}</span>
                                                </button>

                                                <a href="{{ route('user.suggestion.detail', $suggestion->id) }}"
                                                   class="btn-details">
                                                    Detayları Gör →
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div style="padding: 2rem; text-align: center;">
                            <svg style="width: 3rem; height: 3rem; margin: 0 auto 1rem; color: var(--green-400);" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p style="color: var(--gray-500);">Bu proje için henüz öneri bulunmuyor.</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center section-padding-lg">
            <div class="user-card" style="max-width: 400px; margin: 0 auto; padding: 3rem;">
                <svg style="width: 4rem; height: 4rem; margin: 0 auto 1rem; color: var(--green-400);" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                </svg>
                <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-900); margin-bottom: 0.5rem;">Henüz proje bulunmuyor</h3>
                <p style="color: var(--gray-500);">İlk projelerin eklenmesi bekleniyor.</p>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- JavaScript for interactions -->
<script>
// Scroll to project
function scrollToProject(projectId) {
    const element = document.getElementById('project-' + projectId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
        element.style.border = '2px solid var(--green-500)';
        element.style.borderOpacity = '0.5';
        setTimeout(() => {
            element.style.border = '1px solid var(--green-100)';
        }, 2000);
    }
}

// Scroll to suggestion
function scrollToSuggestion(suggestionId) {
    const element = document.getElementById('suggestion-' + suggestionId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'center' });
        element.style.border = '2px solid var(--green-500)';
        element.style.borderOpacity = '0.5';
        setTimeout(() => {
            element.style.border = '1px solid var(--green-200)';
        }, 2000);
    }
}

// Toggle like with AJAX
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

    // Disable button during request
    button.disabled = true;
    button.classList.add('loading');

    $.ajax({
        url: `/suggestions/${suggestionId}/toggle-like`,
        method: 'POST',
        success: function(response) {
            // Update like count
            likeCount.textContent = response.likes_count;

            // Update button appearance
            if (response.liked) {
                button.classList.add('liked');
            } else {
                button.classList.remove('liked');
            }

            // Show success message
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
            // Re-enable button
            button.disabled = false;
            button.classList.remove('loading');
        }
    });
}

// Show message function
function showMessage(message, type) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}`;
    messageDiv.textContent = message;

    document.body.appendChild(messageDiv);

    setTimeout(() => {
        messageDiv.remove();
    }, 3000);
}

// Responsive grid adjustment for mobile
function adjustLayout() {
    const container = document.querySelector('[style*="grid-template-columns: 1fr 3fr"]');
    if (container && window.innerWidth < 1024) {
        container.style.gridTemplateColumns = '1fr';
        container.style.gap = '1rem';
    } else if (container) {
        container.style.gridTemplateColumns = '1fr 3fr';
        container.style.gap = '2rem';
    }
}

// Call on load and resize
window.addEventListener('load', adjustLayout);
window.addEventListener('resize', adjustLayout);
</script>
    </div>
</div>
@endsection
