@extends('user.layout')

@section('title', 'Projeler - DUT Vote')

@section('content')
<!-- Header Section -->
<section class="section-padding" style="padding: 3rem 0; background: #f8fafc;">
    <div class="user-container">
        <!-- Page Header -->
        <div class="text-center" style="margin-bottom: 3rem;">
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 2rem;">
                <svg style="width: 3rem; height: 3rem; margin-right: 1rem; color: var(--green-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                </svg>
                <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--gray-900); margin: 0;">Tüm Projeler</h1>
            </div>

            @php
                // Genel istatistikler
                $totalProjects = $projects->count();
                $totalSuggestions = $projects->sum(function($project) { return $project->oneriler->count(); });
                $totalLikes = $projects->sum(function($project) {
                    return $project->oneriler->sum(function($suggestion) {
                        return $suggestion->likes->count();
                    });
                });
            @endphp

            @if($totalProjects > 0)
            <!-- Statistics Cards -->
            <div style="display: flex; justify-content: center; gap: 2rem; max-width: 800px; margin: 0 auto;">
                <!-- Total Projects Card -->
                <div style="background: white; border-radius: 1rem; padding: 2rem; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0,0,0,0.05); flex: 1; min-width: 200px;">
                    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                        <div style="background: var(--green-100); border-radius: 50%; padding: 0.75rem; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 2rem; height: 2rem; color: var(--green-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 style="font-size: 2.5rem; font-weight: 700; color: var(--green-700); margin: 0 0 0.5rem 0;">{{ $totalProjects }}</h3>
                    <p style="font-size: 1rem; color: var(--gray-600); margin: 0; font-weight: 500;">Toplam Proje</p>
                </div>

                <!-- Total Suggestions Card -->
                <div style="background: white; border-radius: 1rem; padding: 2rem; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0,0,0,0.05); flex: 1; min-width: 200px;">
                    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                        <div style="background: var(--blue-100); border-radius: 50%; padding: 0.75rem; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 2rem; height: 2rem; color: var(--blue-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 style="font-size: 2.5rem; font-weight: 700; color: var(--blue-700); margin: 0 0 0.5rem 0;">{{ $totalSuggestions }}</h3>
                    <p style="font-size: 1rem; color: var(--gray-600); margin: 0; font-weight: 500;">Toplam Öneri</p>
                </div>

                <!-- Total Likes Card -->
                <div style="background: white; border-radius: 1rem; padding: 2rem; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0,0,0,0.05); flex: 1; min-width: 200px;">
                    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                        <div style="background: var(--red-100); border-radius: 50%; padding: 0.75rem; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 2rem; height: 2rem; color: var(--red-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 style="font-size: 2.5rem; font-weight: 700; color: var(--red-600); margin: 0 0 0.5rem 0;">{{ $totalLikes }}</h3>
                    <p style="font-size: 1rem; color: var(--gray-600); margin: 0; font-weight: 500;">Toplam Beğeni</p>
                </div>
            </div>
            @endif
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
                        <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem; color: var(--green-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
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
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem; color: var(--green-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
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
                                    <svg style="width: 0.75rem; height: 0.75rem; margin-right: 0.5rem; color: var(--green-500);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
                                    </svg>
                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ Str::limit($suggestion->title, 20) }}</span>
                                    <span style="margin-left: auto; display: flex; align-items: center;">
                                        <svg style="width: 0.75rem; height: 0.75rem; color: var(--green-600); margin-right: 0.25rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
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
                                <svg style="width: 1.5rem; height: 1.5rem; margin-right: 0.75rem; color: rgba(255,255,255,0.9);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                                </svg>
                                <h2 style="font-size: 1.75rem; font-weight: 700; color: white; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">{{ $project->name }}</h2>
                            </div>

                            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem; color: rgba(255,255,255,0.8);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                                <span style="font-size: 0.875rem; color: rgba(255,255,255,0.9); text-shadow: 0 1px 2px rgba(0,0,0,0.5);">
                                    Proje Yöneticisi: {{ $project->createdBy->name ?? 'Anonim' }}
                                </span>
                            </div>

                            <p style="font-size: 1rem; color: rgba(255,255,255,0.9); margin-bottom: 1.5rem; text-shadow: 0 1px 2px rgba(0,0,0,0.5); line-height: 1.5;">
                                <svg style="width: 1rem; height: 1rem; display: inline; margin-right: 0.5rem; color: rgba(255,255,255,0.8);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
                                </svg>
                                {{ $project->description }}
                            </p>

                            <!-- Project Meta -->
                            <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; color: rgba(255,255,255,0.9); font-size: 0.875rem;">
                                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
                                </svg>
                                {{ $project->oneriler->count() }} Öneri
                            </div>                                @if($project->district)
                                <div style="display: flex; align-items: center; gap: 0.5rem; color: rgba(255,255,255,0.9); font-size: 0.875rem;">
                                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                                    </svg>
                                    {{ $project->district }}, {{ $project->neighborhood }}
                                </div>
                                @endif

                                @if($project->end_datetime)
                                <div style="display: flex; align-items: center; gap: 0.5rem; color: rgba(255,255,255,0.9); font-size: 0.875rem;">
                                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                    </svg>
                                    Bitiş: {{ $project->end_datetime->format('d.m.Y') }}
                                </div>
                                @endif

                                <div style="display: flex; align-items: center; gap: 0.5rem; color: rgba(255,255,255,0.9); font-size: 0.875rem;">
                                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                    </svg>
                                    {{ $project->created_at->format('d.m.Y') }} tarihinde oluşturuldu
                                </div>
                            </div>
                        </div>

                        <!-- Suggestions -->
                        @if($project->oneriler->count() > 0)
                        <div style="position: relative; z-index: 3; padding: 0 2rem 2rem 2rem;">
                            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                                <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem; color: white;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
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
                                                <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem; color: rgba(255,255,255,0.8);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
                                                </svg>
                                                <h4 style="font-size: 1rem; font-weight: 600; color: white; margin: 0; text-shadow: 0 1px 2px rgba(0,0,0,0.5); line-height: 1.2;">{{ $suggestion->title }}</h4>
                                            </div>

                                            <div style="display: flex; align-items: center; margin-bottom: 0.75rem;">
                                                <svg style="width: 0.875rem; height: 0.875rem; margin-right: 0.5rem; color: rgba(255,255,255,0.7);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
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
                                                    <svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                                    </svg>
                                                    <span class="like-count">{{ $suggestion->likes->count() }}</span>
                                                </button>

                                                <!-- Comments Count -->
                                                <div style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 0.375rem 0.75rem; border-radius: var(--radius-md); font-size: 0.75rem; display: flex; align-items: center; gap: 0.25rem; backdrop-filter: blur(4px);">
                                                    <svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/>
                                                    </svg>
                                                    {{ $suggestion->comments->count() }}
                                                </div>
                                            </div>

                                            <!-- Details Button -->
                                            <a href="{{ route('user.suggestion.detail', $suggestion->id) }}"
                                               style="background: rgba(255,255,255,0.9); color: var(--green-700); padding: 0.375rem 0.75rem; border-radius: var(--radius-md); font-size: 0.75rem; font-weight: 500; text-decoration: none; transition: all 0.2s; backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.5); display: flex; align-items: center; gap: 0.25rem;">
                                                <svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
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
                                        <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem; color: rgba(255,255,255,0.7);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
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
                            <svg style="width: 2.5rem; height: 2.5rem; margin: 0 auto 1rem; color: rgba(255,255,255,0.6);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
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
                <svg style="width: 4rem; height: 4rem; margin: 0 auto 1rem; color: var(--green-400);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                </svg>
                <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                    <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem; color: var(--green-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z"/>
                    </svg>
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-900); margin: 0;">Henüz proje bulunmuyor</h3>
                </div>
                <p style="color: var(--gray-500);">
                    <svg style="width: 1rem; height: 1rem; display: inline; margin-right: 0.5rem; color: var(--green-500);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
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
            window.location.href = '{{ route("user.login") }}';
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

    // Adjust stats cards for mobile
    const statsContainer = document.querySelector('[style*="display: flex; justify-content: center; gap: 2rem"]');
    if (statsContainer && window.innerWidth < 768) {
        statsContainer.style.flexDirection = 'column';
        statsContainer.style.gap = '1rem';
        statsContainer.style.maxWidth = '400px';
    } else if (statsContainer) {
        statsContainer.style.flexDirection = 'row';
        statsContainer.style.gap = '2rem';
        statsContainer.style.maxWidth = '800px';
    }

    // Adjust featured content grid for mobile
    const featuredGrids = document.querySelectorAll('[style*="grid-template-columns: 1fr 1fr"]:not([style*="gap: 1rem"])');
    featuredGrids.forEach(grid => {
        if (window.innerWidth < 768) {
            grid.style.gridTemplateColumns = '1fr';
            grid.style.gap = '1.5rem';
        } else {
            grid.style.gridTemplateColumns = '1fr 1fr';
            grid.style.gap = '2rem';
        }
    });
}

// Add hover effects for like buttons and action buttons
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

    // Add hover effects for action buttons
    const actionButtons = document.querySelectorAll('a[style*="background: var(--red-600)"]');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.background = 'var(--red-700)';
            this.style.transform = 'translateY(-1px)';
        });

        button.addEventListener('mouseleave', function() {
            this.style.background = 'var(--red-600)';
            this.style.transform = 'translateY(0)';
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
