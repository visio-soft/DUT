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
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                <svg style="width: 3rem; height: 3rem; margin-right: 1rem; color: var(--green-600);" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                </svg>
                <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--gray-900); margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Tüm Projeler</h1>
            </div>
            <p style="font-size: 1.125rem; color: var(--gray-700); max-width: 600px; margin: 0 auto; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                <svg style="width: 1.25rem; height: 1.25rem; display: inline; margin-right: 0.5rem; color: var(--green-600);" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
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
                    <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                        <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem; color: var(--green-600);" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a3 3 0 003 3h2a3 3 0 003-3V3a2 2 0 012 2v6h-3V8a1 1 0 10-2 0v3H4V5zM2 13a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2v-2z" clip-rule="evenodd"/>
                        </svg>
                        <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-900); margin: 0;">Proje Listesi</h3>
                    </div>
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
                    <div id="project-{{ $project->id }}" class="user-card" style="overflow: hidden; position: relative; min-height: 200px;">
                        <!-- Project Background Image -->
                        @if($project->getFirstMediaUrl('project_files'))
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 1;">
                            <img src="{{ $project->getFirstMediaUrl('project_files') }}"
                                 alt="{{ $project->name }}"
                                 style="width: 100%; height: 100%; object-fit: cover; filter: brightness(0.3);"
                                 onerror="this.style.display='none';">
                        </div>
                        @else
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 1; background: linear-gradient(135deg, var(--green-600) 0%, var(--green-700) 100%); opacity: 0.8;"></div>
                        @endif

                        <!-- Project Overlay -->
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); z-index: 2;"></div>

                        <!-- Project Content -->
                        <div style="position: relative; z-index: 3; padding: 2rem; color: white;">
                            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                                <svg style="width: 1.5rem; height: 1.5rem; margin-right: 0.75rem; color: rgba(255,255,255,0.9);" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                </svg>
                                <h2 style="font-size: 1.75rem; font-weight: 700; color: white; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">{{ $project->name }}</h2>
                            </div>

                            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem; color: rgba(255,255,255,0.8);" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                                </svg>
                                <span style="font-size: 0.875rem; color: rgba(255,255,255,0.9); text-shadow: 0 1px 2px rgba(0,0,0,0.5);">
                                    Proje Yöneticisi: {{ $project->createdBy->name ?? 'Anonim' }}
                                </span>
                            </div>

                            <p style="font-size: 1rem; color: rgba(255,255,255,0.9); margin-bottom: 1.5rem; text-shadow: 0 1px 2px rgba(0,0,0,0.5); line-height: 1.5;">
                                <svg style="width: 1rem; height: 1rem; display: inline; margin-right: 0.5rem; color: rgba(255,255,255,0.8);" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                </svg>
                                {{ $project->description }}
                            </p>

                            <!-- Project Meta -->
                            <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; color: rgba(255,255,255,0.9); font-size: 0.875rem;">
                                    <svg style="width: 1rem; height: 1rem;" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $project->oneriler->count() }} Öneri
                                </div>

                                @if($project->district)
                                <div style="display: flex; align-items: center; gap: 0.5rem; color: rgba(255,255,255,0.9); font-size: 0.875rem;">
                                    <svg style="width: 1rem; height: 1rem;" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $project->district }}, {{ $project->neighborhood }}
                                </div>
                                @endif

                                @if($project->end_datetime)
                                <div style="display: flex; align-items: center; gap: 0.5rem; color: rgba(255,255,255,0.9); font-size: 0.875rem;">
                                    <svg style="width: 1rem; height: 1rem;" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"/>
                                    </svg>
                                    Bitiş: {{ $project->end_datetime->format('d.m.Y') }}
                                </div>
                                @endif

                                <div style="display: flex; align-items: center; gap: 0.5rem; color: rgba(255,255,255,0.9); font-size: 0.875rem;">
                                    <svg style="width: 1rem; height: 1rem;" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"/>
                                    </svg>
                                    {{ $project->created_at->format('d.m.Y') }} tarihinde oluşturuldu
                                </div>
                            </div>
                        </div>

                        <!-- Suggestions -->
                        @if($project->oneriler->count() > 0)
                        <div style="position: relative; z-index: 3; padding: 0 2rem 2rem 2rem;">
                            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                                <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem; color: white;" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h3 style="font-size: 1.125rem; font-weight: 600; color: white; margin: 0; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">Öneriler ({{ $project->oneriler->count() }})</h3>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                @foreach($project->oneriler->take(2) as $suggestion)
                                <div id="suggestion-{{ $suggestion->id }}" style="position: relative; min-height: 180px; border-radius: var(--radius-lg); overflow: hidden; border: 1px solid rgba(255,255,255,0.2);">
                                    <!-- Suggestion Background Image -->
                                    @if($suggestion->getFirstMediaUrl('images'))
                                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 1;">
                                        <img src="{{ $suggestion->getFirstMediaUrl('images') }}"
                                             alt="{{ $suggestion->title }}"
                                             style="width: 100%; height: 100%; object-fit: cover; filter: brightness(0.4);"
                                             onerror="this.style.display='none';">
                                    </div>
                                    @else
                                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 1; background: linear-gradient(135deg, var(--green-500) 0%, var(--green-600) 100%); opacity: 0.8;"></div>
                                    @endif

                                    <!-- Suggestion Overlay -->
                                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); z-index: 2;"></div>

                                    <!-- Suggestion Content -->
                                    <div style="position: relative; z-index: 3; padding: 1rem; height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
                                        <div>
                                            <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                                                <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem; color: rgba(255,255,255,0.8);" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                <h4 style="font-size: 1rem; font-weight: 600; color: white; margin: 0; text-shadow: 0 1px 2px rgba(0,0,0,0.5); line-height: 1.2;">{{ $suggestion->title }}</h4>
                                            </div>

                                            <div style="display: flex; align-items: center; margin-bottom: 0.75rem;">
                                                <svg style="width: 0.875rem; height: 0.875rem; margin-right: 0.5rem; color: rgba(255,255,255,0.7);" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                                                </svg>
                                                <span style="font-size: 0.75rem; color: rgba(255,255,255,0.8); text-shadow: 0 1px 2px rgba(0,0,0,0.5);">
                                                    {{ $suggestion->createdBy->name ?? 'Anonim' }}
                                                </span>
                                            </div>

                                            <p style="font-size: 0.875rem; color: rgba(255,255,255,0.9); margin-bottom: 1rem; text-shadow: 0 1px 2px rgba(0,0,0,0.5); line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ Str::limit($suggestion->description, 80) }}</p>
                                        </div>

                                        <!-- Suggestion Actions -->
                                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
                                            <div style="display: flex; align-items: center; gap: 1rem;">
                                                <!-- Like Button -->
                                                <button onclick="toggleLike({{ $suggestion->id }})"
                                                        class="btn-like {{ Auth::check() && $suggestion->likes->where('user_id', Auth::id())->count() > 0 ? 'liked' : '' }}"
                                                        data-suggestion-id="{{ $suggestion->id }}"
                                                        style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 0.375rem 0.75rem; border-radius: var(--radius-md); font-size: 0.75rem; display: flex; align-items: center; gap: 0.25rem; transition: all 0.2s; backdrop-filter: blur(4px);">
                                                    <svg style="width: 0.875rem; height: 0.875rem;" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                                                    </svg>
                                                    <span class="like-count">{{ $suggestion->likes->count() }}</span>
                                                </button>

                                                <!-- Comments Count -->
                                                <div style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 0.375rem 0.75rem; border-radius: var(--radius-md); font-size: 0.75rem; display: flex; align-items: center; gap: 0.25rem; backdrop-filter: blur(4px);">
                                                    <svg style="width: 0.875rem; height: 0.875rem;" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $suggestion->comments->count() }}
                                                </div>
                                            </div>

                                            <!-- Details Button -->
                                            <a href="{{ route('user.suggestion.detail', $suggestion->id) }}"
                                               style="background: rgba(255,255,255,0.9); color: var(--green-700); padding: 0.375rem 0.75rem; border-radius: var(--radius-md); font-size: 0.75rem; font-weight: 500; text-decoration: none; transition: all 0.2s; backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.5); display: flex; align-items: center; gap: 0.25rem;">
                                                <svg style="width: 0.875rem; height: 0.875rem;" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                                                </svg>
                                                Detay
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                @if($project->oneriler->count() > 2)
                                <div style="grid-column: 1 / -1; text-align: center; margin-top: 0.5rem;">
                                    <div style="display: flex; align-items: center; justify-content: center;">
                                        <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem; color: rgba(255,255,255,0.7);" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span style="color: rgba(255,255,255,0.8); font-size: 0.875rem; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">
                                            ve {{ $project->oneriler->count() - 2 }} öneri daha...
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @else
                        <div style="position: relative; z-index: 3; padding: 0 2rem 2rem 2rem; text-align: center;">
                            <svg style="width: 2.5rem; height: 2.5rem; margin: 0 auto 1rem; color: rgba(255,255,255,0.6);" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2L3 7v11a2 2 0 002 2h10a2 2 0 002-2V7l-7-5z"/>
                                <path d="M8 15a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/>
                            </svg>
                            <p style="color: rgba(255,255,255,0.8); text-shadow: 0 1px 2px rgba(0,0,0,0.5);">Bu proje için henüz öneri bulunmuyor.</p>
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
                    <path d="M10 2L3 7v11a2 2 0 002 2h10a2 2 0 002-2V7l-7-5z"/>
                    <path d="M8 15a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/>
                </svg>
                <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                    <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem; color: var(--green-600);" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"/>
                    </svg>
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-900); margin: 0;">Henüz proje bulunmuyor</h3>
                </div>
                <p style="color: var(--gray-500);">
                    <svg style="width: 1rem; height: 1rem; display: inline; margin-right: 0.5rem; color: var(--green-500);" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    İlk projelerin eklenmesi bekleniyor.
                </p>
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

    // Adjust suggestion grid for mobile
    const suggestionGrids = document.querySelectorAll('[style*="grid-template-columns: 1fr 1fr"]');
    suggestionGrids.forEach(grid => {
        if (window.innerWidth < 768) {
            grid.style.gridTemplateColumns = '1fr';
            grid.style.gap = '0.75rem';
        } else {
            grid.style.gridTemplateColumns = '1fr 1fr';
            grid.style.gap = '1rem';
        }
    });
}

// Add hover effects for like buttons
document.addEventListener('DOMContentLoaded', function() {
    const likeButtons = document.querySelectorAll('.btn-like');
    likeButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            if (!this.classList.contains('liked')) {
                this.style.background = 'rgba(255,255,255,0.25)';
                this.style.borderColor = 'rgba(255,255,255,0.5)';
            }
        });

        button.addEventListener('mouseleave', function() {
            if (!this.classList.contains('liked')) {
                this.style.background = 'rgba(255,255,255,0.15)';
                this.style.borderColor = 'rgba(255,255,255,0.3)';
            }
        });
    });
});

// Call on load and resize
window.addEventListener('load', adjustLayout);
window.addEventListener('resize', adjustLayout);
</script>
    </div>
</div>
@endsection
