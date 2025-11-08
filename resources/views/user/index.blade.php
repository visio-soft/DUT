@extends('user.layout')

@section('title', __('common.home') . ' - DUT Vote')

@section('content')
<!-- Hero Section -->
<section class="user-hero dynamic-background {{ ($hasBackgroundImages ?? false) ? '' : 'no-background-images' }}">
    @if($hasBackgroundImages ?? false)
        @if($randomBackgroundImage)
            <!-- Single Random Background Image -->
            <div class="background-image-container">
                <img src="{{ $randomBackgroundImage }}"
                     alt="{{ strip_tags(__('common.transform_city_together')) }}"
                     class="background-image-main"
                     loading="eager">
            </div>
        @endif
        <div class="background-image-overlay"></div>
    @endif

    <div class="user-container">
        <div class="user-hero-content">
            <h1>{!! __('common.transform_city_together') !!}</h1>
            <p>
                {{ __('common.hero_description') }}
            </p>
            <div class="user-hero-actions">
                <a href="{{ route('user.projects') }}" class="btn btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                    </svg>
                    {{ __('common.explore_projects') }}
                </a>
                <a href="#projects" class="btn btn-secondary">
                    {{ __('common.more_info') }}
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Projects Section -->
<section id="projects" class="section-padding-lg">
    <div class="user-container">
        <div class="text-center content-spacing-xl">
            <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">{{ __('common.featured_projects') }}</h2>
            <p style="font-size: 1.125rem; color: var(--gray-600); max-width: 600px; margin: 0 auto;">
                {{ __('common.featured_projects_description') }}
            </p>
        </div>

        @if($randomProjects->count() > 0)
            <div class="featured-projects-grid count-{{ $randomProjects->count() }} content-spacing-xl">
                @foreach($randomProjects as $project)
                    <div class="project-card">
                        <!-- Project Image -->
                        <div class="project-image-container">
                            @if($project->getFirstMediaUrl('project_files'))
                                <div class="project-background-with-blur">
                                    <div class="project-background-blur-left" style="background-image: url('{{ $project->getFirstMediaUrl('project_files') }}');"></div>
                                    <div class="project-background-blur-right" style="background-image: url('{{ $project->getFirstMediaUrl('project_files') }}');"></div>
                                    <img src="{{ $project->getFirstMediaUrl('project_files') }}"
                                         alt="{{ $project->name }}"
                                         class="project-center-image"
                                         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=&quot;project-placeholder&quot;><div style=&quot;text-align: center;&quot;><svg style=&quot;width: 3rem; height: 3rem; color: var(--green-600); margin-bottom: 0.5rem;&quot; fill=&quot;currentColor&quot; viewBox=&quot;0 0 20 20&quot;><path fill-rule=&quot;evenodd&quot; d=&quot;M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z&quot; clip-rule=&quot;evenodd&quot;/></svg><p style=&quot;color: var(--green-700); font-size: 0.875rem;&quot;>{{ __("common.project_image") }}</p></div></div>';">
                                </div>
                            @else
                                <div class="project-placeholder">
                                    <div style="text-align: center;">
                                        <svg style="width: 3rem; height: 3rem; color: var(--green-600); margin-bottom: 0.5rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                        </svg>
                                        <p style="color: var(--green-700); font-size: 0.875rem;">{{ __('common.project_image') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Project Content -->
                        <div class="project-header">
                            <h3 class="project-title">{{ $project->name }}</h3>
                            <p class="project-description">{{ Str::limit($project->description, 150) }}</p>

                            <!-- Project Stats -->
                            <div class="project-stats">
                                <div class="project-stat">
                                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
                                    </svg>
                                    {{ $project->suggestions->count() }} {{ __('common.suggestion') }}
                                </div>
                                @if($project->end_datetime)
                                    @php
                                        $remainingTime = $project->getRemainingTime();
                                        $isExpired = $project->isExpired();
                                    @endphp
                                    <div class="project-stat" style="color: {{ $isExpired ? 'var(--red-600)' : 'var(--green-600)' }}; font-weight: 600;">
                                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                        @if($isExpired)
                                            {{ __('common.expired') }}
                                        @elseif($remainingTime)
                                            {{ $remainingTime['formatted'] }} {{ __('common.remaining') }}
                                        @else
                                            {{ __('common.unlimited') }}
                                        @endif
                                    </div>
                                @elseif($project->start_datetime)
                                <div class="project-stat">
                                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                    </svg>
                                    {{ $project->start_datetime->format('d.m.Y') }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Project Actions -->
                        <div class="project-actions">
                            <a href="{{ route('user.projects') }}#project-{{ $project->id }}" class="btn btn-primary project-action-btn">
                                {{ __('common.view_project') }}
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center section-padding">
                <div class="user-card" style="max-width: 400px; margin: 0 auto; padding: 3rem;">
                    <svg style="width: 4rem; height: 4rem; margin: 0 auto 1rem; color: var(--green-400);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--gray-900); margin-bottom: 0.5rem;">{{ __('common.no_projects_yet') }}</h3>
                    <p style="color: var(--gray-500);">{{ __('common.waiting_for_first_projects') }}</p>
                </div>
            </div>
        @endif

        @if($randomProjects->count() > 0)
        <div class="text-center view-all-projects-section">
            <a href="{{ route('user.projects') }}" class="btn btn-secondary">
                {{ __('common.view_all_projects') }}
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </div>
        @endif
    </div>
</section>
@endsection
