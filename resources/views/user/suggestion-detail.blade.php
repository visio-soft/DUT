@extends('user.layout')

@section('title', $suggestion->title . ' - Proje Paneli')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-8">
            <a href="{{ route('user.index') }}" class="hover:text-gray-700">Ana Sayfa</a>
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <a href="{{ route('user.projects') }}" class="hover:text-gray-700">Projeler</a>
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <span class="text-gray-900">{{ Str::limit($suggestion->title, 50) }}</span>
        </nav>

        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <!-- Header -->
            <div class="px-8 py-8 border-b border-gray-100">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $suggestion->title }}</h1>
                        
                        <!-- Project Info -->
                        <div class="flex items-center space-x-2 mb-4">
                            <span class="text-sm text-gray-500">Proje:</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                </svg>
                                {{ $suggestion->category->name }}
                            </span>
                        </div>
                        
                        <!-- Meta Information -->
                        <div class="flex flex-wrap items-center gap-6 text-sm text-gray-500">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                </svg>
                                {{ $suggestion->createdBy ? $suggestion->createdBy->name : 'Anonim' }}
                            </div>
                            
                            @if($suggestion->budget)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                                <span class="font-semibold text-green-700">{{ number_format($suggestion->budget, 0) }} ₺</span>
                            </div>
                            @endif
                            
                            @if($suggestion->estimated_duration)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                {{ $suggestion->estimated_duration }} gün
                            </div>
                            @endif
                            
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"/>
                                </svg>
                                {{ $suggestion->created_at->format('d.m.Y') }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Like Button (Large) -->
                    <div class="flex-shrink-0 ml-6">
                        <button onclick="toggleLike({{ $suggestion->id }})" 
                                class="like-btn flex items-center space-x-2 px-6 py-3 rounded-full text-base font-medium transition duration-200 
                                       {{ Auth::check() && $suggestion->likes->where('user_id', Auth::id())->count() > 0 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600 hover:bg-red-50 hover:text-red-600' }}"
                                data-suggestion-id="{{ $suggestion->id }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                            </svg>
                            <span class="like-count">{{ $suggestion->likes->count() }}</span>
                            <span>Beğeni</span>
                        </button>
                    </div>
                </div>
                
                <!-- Location Info -->
                @if($suggestion->address || $suggestion->district)
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-start space-x-2">
                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-1">Konum</h4>
                            <p class="text-sm text-gray-600">
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
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Content Area -->
                <div class="lg:col-span-2 px-8 py-8">
                    <!-- Suggestion Image -->
                    @if($suggestion->getFirstMediaUrl('images'))
                    <div class="mb-8">
                        <img src="{{ $suggestion->getFirstMediaUrl('images') }}" 
                             alt="{{ $suggestion->title }}"
                             class="w-full h-64 object-cover rounded-lg shadow-sm">
                    </div>
                    @endif
                    
                    <!-- Description -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Açıklama</h3>
                        <div class="prose max-w-none text-gray-600 text-base leading-relaxed">
                            {!! nl2br(e($suggestion->description)) !!}
                        </div>
                    </div>
                    
                    <!-- Comments Section -->
                    @if($suggestion->approvedComments->count() > 0)
                    <div class="border-t border-gray-100 pt-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">
                            Yorumlar ({{ $suggestion->approvedComments->count() }})
                        </h3>
                        
                        <div class="space-y-6">
                            @foreach($suggestion->approvedComments as $comment)
                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-600">
                                                {{ substr($comment->user->name ?? 'A', 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <h4 class="font-medium text-gray-900">{{ $comment->user->name ?? 'Anonim' }}</h4>
                                            <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-600 leading-relaxed">{{ $comment->comment }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Sidebar -->
                <div class="lg:col-span-1 px-8 py-8 bg-gray-50">
                    <!-- Stats -->
                    <div class="mb-8">
                        <h4 class="font-semibold text-gray-900 mb-4">İstatistikler</h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Toplam Beğeni</span>
                                <span class="font-medium text-gray-900">{{ $suggestion->likes->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Toplam Yorum</span>
                                <span class="font-medium text-gray-900">{{ $suggestion->approvedComments->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Oluşturma Tarihi</span>
                                <span class="font-medium text-gray-900">{{ $suggestion->created_at->format('d.m.Y') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Likes -->
                    @if($suggestion->likes->count() > 0)
                    <div class="mb-8">
                        <h4 class="font-semibold text-gray-900 mb-4">Son Beğenenler</h4>
                        <div class="space-y-2">
                            @foreach($suggestion->likes->take(5) as $like)
                            <div class="flex items-center space-x-2 text-sm">
                                <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-600">
                                        {{ substr($like->user->name ?? 'A', 0, 1) }}
                                    </span>
                                </div>
                                <span class="text-gray-600">{{ $like->user->name ?? 'Anonim' }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <!-- Actions -->
                    <div class="space-y-3">
                        <a href="{{ route('user.projects') }}#project-{{ $suggestion->category_id }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0L2.586 11l3.707-3.707a1 1 0 011.414 1.414L5.414 11l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                            </svg>
                            Projeye Dön
                        </a>
                        
                        <a href="{{ route('user.projects') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
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
        alert('Beğeni yapmak için giriş yapmanız gerekiyor.');
        window.location.href = '/admin/login';
        return;
    @endguest

    const button = document.querySelector(`[data-suggestion-id="${suggestionId}"]`);
    const likeCount = button.querySelector('.like-count');
    
    button.disabled = true;
    button.classList.add('opacity-50');
    
    $.ajax({
        url: `/suggestions/${suggestionId}/toggle-like`,
        method: 'POST',
        success: function(response) {
            likeCount.textContent = response.likes_count;
            
            if (response.liked) {
                button.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-red-50', 'hover:text-red-600');
                button.classList.add('bg-red-100', 'text-red-700');
            } else {
                button.classList.remove('bg-red-100', 'text-red-700');
                button.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-red-50', 'hover:text-red-600');
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
            button.classList.remove('opacity-50');
        }
    });
}

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
</script>
@endsection