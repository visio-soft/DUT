@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        <svg class="w-8 h-8 inline-block mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Proje Kategorileri
                    </h1>
                    <p class="text-gray-600 mt-1">Tüm projeleri kategorilere göre düzenlenmiş halde görüntüleyin</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">
                        Toplam {{ $categories->sum('total_items') }} proje/öneri
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        @if($categories->count() > 0)
            <div class="space-y-6">
                @foreach($categories as $categoryData)
                    <div class="category-container bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <!-- Category Header (Folder) -->
                        <div class="category-header cursor-pointer hover:bg-gray-50 transition-colors duration-200" 
                             onclick="toggleCategory('category-{{ $categoryData['category']->id }}')">
                            <div class="flex items-center justify-between p-6">
                                <div class="flex items-center space-x-3">
                                    <!-- Folder Icon -->
                                    <svg class="folder-icon w-6 h-6 text-yellow-500 transition-transform duration-200" 
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                                    </svg>
                                    
                                    <!-- Category Info -->
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ $categoryData['category']->name }}
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            {{ $categoryData['projects']->count() }} proje, 
                                            {{ $categoryData['suggestions']->count() }} öneri
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    @if($categoryData['category']->end_datetime)
                                        <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                                            {{ \Carbon\Carbon::parse($categoryData['category']->end_datetime)->format('d.m.Y') }} bitiş
                                        </span>
                                    @endif
                                    
                                    <!-- Expand/Collapse Arrow -->
                                    <svg class="expand-arrow w-5 h-5 text-gray-400 transition-transform duration-200" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Category Content (Initially Hidden) -->
                        <div id="category-{{ $categoryData['category']->id }}" class="category-content hidden">
                            <div class="border-t border-gray-200 bg-gray-50">
                                
                                @if($categoryData['projects']->count() > 0)
                                    <!-- Projects Section -->
                                    <div class="p-6">
                                        <h4 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Projeler ({{ $categoryData['projects']->count() }})
                                        </h4>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            @foreach($categoryData['projects'] as $project)
                                                <div class="project-card bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow duration-200">
                                                    <div class="flex items-start justify-between mb-3">
                                                        <h5 class="font-medium text-gray-900 flex-1">
                                                            <a href="{{ route('public.projects.show', $project->id) }}" 
                                                               class="hover:text-blue-600 transition-colors">
                                                                {{ $project->title }}
                                                            </a>
                                                        </h5>
                                                    </div>
                                                    
                                                    @if($project->description)
                                                        <p class="text-sm text-gray-600 mb-3 line-clamp-3">
                                                            {{ Str::limit($project->description, 120) }}
                                                        </p>
                                                    @endif
                                                    
                                                    <div class="flex items-center justify-between text-xs text-gray-500">
                                                        <span>{{ $project->createdBy->name ?? 'Anonim' }}</span>
                                                        <span>{{ $project->created_at->format('d.m.Y') }}</span>
                                                    </div>
                                                    
                                                    @if($project->budget)
                                                        <div class="mt-2">
                                                            <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800">
                                                                ₺{{ number_format($project->budget, 0, ',', '.') }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if($categoryData['suggestions']->count() > 0)
                                    <!-- Suggestions Section -->
                                    <div class="p-6 border-t border-gray-200">
                                        <h4 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                            </svg>
                                            Öneriler ({{ $categoryData['suggestions']->count() }})
                                        </h4>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            @foreach($categoryData['suggestions'] as $suggestion)
                                                <div class="suggestion-card bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow duration-200">
                                                    <div class="flex items-start justify-between mb-3">
                                                        <h5 class="font-medium text-gray-900 flex-1">
                                                            <a href="{{ route('public.projects.suggestion', $suggestion->id) }}" 
                                                               class="hover:text-blue-600 transition-colors">
                                                                {{ $suggestion->title }}
                                                            </a>
                                                        </h5>
                                                    </div>
                                                    
                                                    @if($suggestion->description)
                                                        <p class="text-sm text-gray-600 mb-3 line-clamp-3">
                                                            {{ Str::limit($suggestion->description, 120) }}
                                                        </p>
                                                    @endif
                                                    
                                                    <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                                        <span>{{ $suggestion->createdBy->name ?? 'Anonim' }}</span>
                                                        <span>{{ $suggestion->created_at->format('d.m.Y') }}</span>
                                                    </div>
                                                    
                                                    @if($suggestion->project)
                                                        <div class="text-xs text-blue-600 mb-2">
                                                            Bağlı proje: {{ $suggestion->project->title }}
                                                        </div>
                                                    @endif
                                                    
                                                    <div class="flex items-center space-x-3 text-xs text-gray-500">
                                                        @if($suggestion->estimated_duration)
                                                            <span class="flex items-center">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                {{ $suggestion->estimated_duration }} gün
                                                            </span>
                                                        @endif
                                                        @if($suggestion->likes_count > 0)
                                                            <span class="flex items-center">
                                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"></path>
                                                                </svg>
                                                                {{ $suggestion->likes_count }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Henüz proje bulunmuyor</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Sistem henüz hiçbir proje kategorisi içermiyor.
                </p>
            </div>
        @endif
    </div>
</div>

<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.category-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out;
}

.category-content.expanded {
    max-height: 2000px;
}
</style>

<script>
function toggleCategory(categoryId) {
    const content = document.getElementById(categoryId);
    const header = content.previousElementSibling;
    const arrow = header.querySelector('.expand-arrow');
    const folderIcon = header.querySelector('.folder-icon');
    
    if (content.classList.contains('expanded')) {
        // Collapse
        content.classList.remove('expanded');
        content.classList.add('hidden');
        arrow.style.transform = 'rotate(0deg)';
        folderIcon.style.transform = 'rotate(0deg)';
    } else {
        // Expand
        content.classList.remove('hidden');
        content.classList.add('expanded');
        arrow.style.transform = 'rotate(180deg)';
        folderIcon.style.transform = 'rotate(15deg)';
    }
}

// Optional: Expand/collapse all functionality
function toggleAllCategories() {
    const allContents = document.querySelectorAll('.category-content');
    const anyExpanded = Array.from(allContents).some(content => content.classList.contains('expanded'));
    
    allContents.forEach(content => {
        const categoryId = content.id;
        if (anyExpanded) {
            // Collapse all
            content.classList.remove('expanded');
            content.classList.add('hidden');
        } else {
            // Expand all
            content.classList.remove('hidden');
            content.classList.add('expanded');
        }
        
        const header = content.previousElementSibling;
        const arrow = header.querySelector('.expand-arrow');
        const folderIcon = header.querySelector('.folder-icon');
        
        if (anyExpanded) {
            arrow.style.transform = 'rotate(0deg)';
            folderIcon.style.transform = 'rotate(0deg)';
        } else {
            arrow.style.transform = 'rotate(180deg)';
            folderIcon.style.transform = 'rotate(15deg)';
        }
    });
}
</script>
@endsection
