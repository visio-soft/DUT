@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('public.projects.index') }}"
                   class="text-gray-500 hover:text-gray-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $project->title }}</h1>
                    <p class="text-gray-600 mt-1">
                        <a href="{{ route('public.projects.index') }}" class="hover:text-blue-600">Proje Kategorileri</a>
                        <span class="mx-2">/</span>
                        <span class="text-blue-600">{{ $project->category->name ?? 'Kategorisiz' }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Project Details Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <!-- Project Image -->
                    @if($project->hasMedia('images'))
                        <div class="mb-6">
                            <img src="{{ $project->getFirstMediaUrl('images') }}"
                                 alt="{{ $project->title }}"
                                 class="w-full h-64 object-cover rounded-lg">
                        </div>
                    @endif

                    <!-- Project Description -->
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Proje Açıklaması</h3>
                        <div class="prose prose-sm max-w-none">
                            {!! nl2br(e($project->description)) !!}
                        </div>
                    </div>

                    <!-- Project Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                        @if($project->budget)
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                <span class="text-sm text-gray-600">Bütçe:</span>
                                <span class="font-medium text-green-600">₺{{ number_format((float)$project->budget, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        @if($project->estimated_duration)
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-gray-600">Tahmini Süre:</span>
                                <span class="font-medium">{{ $project->estimated_duration }} gün</span>
                            </div>
                        @endif

                        @if($project->city)
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-sm text-gray-600">Konum:</span>
                                <span class="font-medium">
                                    {{ $project->city }}
                                    @if($project->district), {{ $project->district }}@endif
                                    @if($project->neighborhood), {{ $project->neighborhood }}@endif
                                </span>
                            </div>
                        @endif

                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">{{ __('common.creation') }}:</span>
                            <span class="font-medium">{{ $project->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Related Suggestions -->
                @if($project->suggestions->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                            Bağlı Öneriler ({{ $project->suggestions->count() }})
                        </h3>

                        <div class="space-y-4">
                            @foreach($project->suggestions as $suggestion)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900 mb-2">
                                                <a href="{{ route('public.projects.suggestion', $suggestion->id) }}"
                                                   class="hover:text-blue-600 transition-colors">
                                                    {{ $suggestion->title }}
                                                </a>
                                            </h4>
                                            @if($suggestion->description)
                                                <p class="text-sm text-gray-600 mb-2">
                                                    {{ Str::limit($suggestion->description, 200) }}
                                                </p>
                                            @endif
                                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                                @if($suggestion->createdBy)
                                                <span>{{ $suggestion->createdBy->name }}</span>
                                                @endif
                                                <span>{{ $suggestion->created_at->format('d.m.Y') }}</span>
                                                @if($suggestion->likes_count > 0)
                                                    <span class="flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"></path>
                                                        </svg>
                                                        {{ $suggestion->likes_count }} beğeni
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-4">
                    <!-- Project Info -->
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Proje Bilgileri</h3>

                    <div class="space-y-4">
                        <!-- Category -->
                        <div>
                            <span class="text-sm font-medium text-gray-500">Kategori</span>
                            <p class="text-sm text-gray-900 mt-1">{{ $project->category->name ?? 'Kategorisiz' }}</p>
                        </div>

                        <!-- Creator -->
                        @if($project->createdBy)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Oluşturan</span>
                            <p class="text-sm text-gray-900 mt-1">{{ $project->createdBy->name }}</p>
                        </div>
                        @endif

                        @if($project->start_date || $project->end_date)
                            <!-- Project Timeline -->
                            <div>
                                <span class="text-sm font-medium text-gray-500">Proje Süresi</span>
                                <div class="text-sm text-gray-900 mt-1">
                                    @if($project->start_date)
                                        <p>Başlangıç: {{ \Carbon\Carbon::parse($project->start_date)->format('d.m.Y') }}</p>
                                    @endif
                                    @if($project->end_date)
                                        <p>{{ __('common.project_end') }}: {{ \Carbon\Carbon::parse($project->end_date)->format('d.m.Y') }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Stats -->
                        <div class="pt-4 border-t border-gray-200">
                            <span class="text-sm font-medium text-gray-500">İstatistikler</span>
                            <div class="text-sm text-gray-900 mt-2 space-y-1">
                                <p>{{ $project->likes_count }} beğeni</p>
                                <p>{{ $project->suggestions->count() }} bağlı öneri</p>
                                <p>{{ $project->created_at->diffForHumans() }} oluşturuldu</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-4 border-t border-gray-200 space-y-2">
                            <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                Projeyi Beğen
                            </button>
                            <button class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                                Öneri Ekle
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
