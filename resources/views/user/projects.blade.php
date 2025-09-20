@extends('user.layout')

@section('title', 'Projeler - Proje Paneli')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900">Tüm Projeler</h1>
            <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">
                Şehrimizi güzelleştirmek için hazırlanan projeler ve yaratıcı öneriler
            </p>
        </div>

        @if($projects->count() > 0)
        <div class="lg:grid lg:grid-cols-4 lg:gap-8">
            <!-- Sol Taraf: Tree View -->
            <div class="lg:col-span-1 mb-8 lg:mb-0">
                <div class="bg-white rounded-lg shadow-sm border p-6 sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Proje Listesi</h3>
                    <div class="space-y-2">
                        @foreach($projects as $project)
                        <div class="border-b border-gray-100 pb-2">
                            <!-- Project Node -->
                            <div class="flex items-center cursor-pointer py-2 px-2 rounded hover:bg-gray-50 tree-project" 
                                 data-project-id="{{ $project->id }}"
                                 onclick="scrollToProject({{ $project->id }})">
                                <svg class="w-4 h-4 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-900 truncate">{{ Str::limit($project->name, 25) }}</span>
                                <span class="ml-auto text-xs text-gray-500">({{ $project->oneriler->count() }})</span>
                            </div>
                            
                            <!-- Suggestions -->
                            @if($project->oneriler->count() > 0)
                            <div class="ml-6 mt-1 space-y-1">
                                @foreach($project->oneriler as $suggestion)
                                <div class="flex items-center py-1 px-2 text-xs text-gray-600 hover:text-gray-900 cursor-pointer hover:bg-gray-50 rounded"
                                     onclick="scrollToSuggestion({{ $suggestion->id }})">
                                    <svg class="w-3 h-3 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="truncate">{{ Str::limit($suggestion->title, 20) }}</span>
                                    <span class="ml-auto flex items-center">
                                        <svg class="w-3 h-3 text-red-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
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
            <div class="lg:col-span-3">
                <div class="space-y-8">
                    @foreach($projects as $project)
                    <div id="project-{{ $project->id }}" class="bg-white rounded-xl shadow-sm border overflow-hidden">
                        <!-- Project Header -->
                        <div class="px-6 py-6 border-b border-gray-100">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $project->name }}</h2>
                                    <p class="text-gray-600 text-base leading-relaxed mb-4">
                                        {{ $project->description }}
                                    </p>
                                    
                                    <!-- Project Meta -->
                                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $project->oneriler->count() }} Öneri
                                        </div>
                                        
                                        @if($project->district)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $project->district }}, {{ $project->neighborhood }}
                                        </div>
                                        @endif
                                        
                                        @if($project->start_datetime)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"/>
                                            </svg>
                                            {{ $project->start_datetime->format('d.m.Y') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Project Image -->
                                @if($project->getFirstMediaUrl('project_files'))
                                <div class="ml-6 flex-shrink-0">
                                    <div class="w-32 h-24 bg-gray-200 rounded-lg overflow-hidden">
                                        <img src="{{ $project->getFirstMediaUrl('project_files') }}" 
                                             alt="{{ $project->name }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Suggestions -->
                        @if($project->oneriler->count() > 0)
                        <div class="px-6 py-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Öneriler ({{ $project->oneriler->count() }})</h3>
                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2">
                                @foreach($project->oneriler as $suggestion)
                                <div id="suggestion-{{ $suggestion->id }}" class="bg-gray-50 rounded-lg p-4 border hover:shadow-sm transition duration-200">
                                    <div class="flex items-start gap-4">
                                        <!-- Suggestion Image -->
                                        <div class="flex-shrink-0">
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden">
                                                @if($suggestion->getFirstMediaUrl('images'))
                                                    <img src="{{ $suggestion->getFirstMediaUrl('images') }}" 
                                                         alt="{{ $suggestion->title }}"
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Suggestion Content -->
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-900 mb-1 truncate">{{ $suggestion->title }}</h4>
                                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($suggestion->description, 100) }}</p>
                                            
                                            <!-- Suggestion Meta -->
                                            @if($suggestion->budget)
                                            <div class="text-xs text-gray-500 mb-3">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-800">
                                                    💰 {{ number_format($suggestion->budget, 0) }} ₺
                                                </span>
                                            </div>
                                            @endif
                                            
                                            <!-- Action Buttons -->
                                            <div class="flex items-center justify-between">
                                                <button onclick="toggleLike({{ $suggestion->id }})" 
                                                        class="like-btn flex items-center space-x-1 px-3 py-1 rounded-full text-sm transition duration-200 
                                                               {{ Auth::check() && $suggestion->likes->where('user_id', Auth::id())->count() > 0 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600 hover:bg-red-50 hover:text-red-600' }}"
                                                        data-suggestion-id="{{ $suggestion->id }}">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                                                    </svg>
                                                    <span class="like-count">{{ $suggestion->likes->count() }}</span>
                                                </button>
                                                
                                                <a href="{{ route('user.suggestion.detail', $suggestion->id) }}" 
                                                   class="text-sm text-blue-600 hover:text-blue-800 font-medium">
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
                        <div class="px-6 py-8 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-gray-500">Bu proje için henüz öneri bulunmuyor.</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="bg-white rounded-lg shadow-sm border p-12 max-w-md mx-auto">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Henüz proje bulunmuyor</h3>
                <p class="text-gray-500">İlk projelerin eklenmesi bekleniyor.</p>
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
        element.classList.add('ring-2', 'ring-blue-500', 'ring-opacity-50');
        setTimeout(() => {
            element.classList.remove('ring-2', 'ring-blue-500', 'ring-opacity-50');
        }, 2000);
    }
}

// Scroll to suggestion
function scrollToSuggestion(suggestionId) {
    const element = document.getElementById('suggestion-' + suggestionId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'center' });
        element.classList.add('ring-2', 'ring-green-500', 'ring-opacity-50');
        setTimeout(() => {
            element.classList.remove('ring-2', 'ring-green-500', 'ring-opacity-50');
        }, 2000);
    }
}

// Toggle like with AJAX
function toggleLike(suggestionId) {
    @guest
        alert('Beğeni yapmak için giriş yapmanız gerekiyor.');
        window.location.href = '/admin/login';
        return;
    @endguest

    const button = document.querySelector(`[data-suggestion-id="${suggestionId}"]`);
    const likeCount = button.querySelector('.like-count');
    
    // Disable button during request
    button.disabled = true;
    button.classList.add('opacity-50');
    
    $.ajax({
        url: `/suggestions/${suggestionId}/toggle-like`,
        method: 'POST',
        success: function(response) {
            // Update like count
            likeCount.textContent = response.likes_count;
            
            // Update button appearance
            if (response.liked) {
                button.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-red-50', 'hover:text-red-600');
                button.classList.add('bg-red-100', 'text-red-700');
            } else {
                button.classList.remove('bg-red-100', 'text-red-700');
                button.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-red-50', 'hover:text-red-600');
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
            button.classList.remove('opacity-50');
        }
    });
}

// Show message function
function showMessage(message, type) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `fixed top-4 right-4 px-4 py-2 rounded-lg text-white z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    messageDiv.textContent = message;
    
    document.body.appendChild(messageDiv);
    
    setTimeout(() => {
        messageDiv.remove();
    }, 3000);
}

// Line clamp utility for older browsers
const style = document.createElement('style');
style.textContent = `
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
`;
document.head.appendChild(style);
</script>
@endsection