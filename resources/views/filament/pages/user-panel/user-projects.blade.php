@extends('filament.pages.user-panel.layout')

@section('title', __('common.projects') . ' - DUT Vote')

@section('content')
<!-- Header Section -->
<section class="section-padding" style="padding: 3rem 0; background: #f8fafc;">
    {{-- shared colors/vars --}}
    @include('filament.pages.user-panel._shared-colors')

<style>
    /* Header Styles */
    .page-header {
        padding: 3rem 0;
        background: #f8fafc;
    }

    .header-content {
        margin-bottom: 3rem;
        text-align: center;
    }

    .header-title-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        gap: 1rem;
    }

    .project-icon {
        width: 3rem;
        height: 3rem;
        color: var(--green-600);
    }

    .project-title-section h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0;
    }

    /* Statistics Section */
    .stats-section {
        position: relative;
        padding: 2rem 0;
        margin-bottom: 2rem;
    }

    .stats-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
        padding: 0 1rem;
    }

    .stats-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--gray-800);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        max-width: 900px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .stat-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: var(--radius-xl);
        padding: 2rem 1.5rem;
        text-align: center;
        border: 1px solid var(--gray-200);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }


    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
        border-color: var(--gray-300);
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-icon-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
    }

    .stat-icon {
        border-radius: 50%;
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        transition: all 0.3s ease;
    }

    .stat-icon.green {
        background: linear-gradient(135deg, var(--green-100), var(--green-200));
        border: 2px solid var(--green-300);
    }

    .stat-icon.blue {
        background: linear-gradient(135deg, var(--blue-100), #bfdbfe);
        border: 2px solid #93c5fd;
    }

    .stat-icon.red {
        background: linear-gradient(135deg, #fef2f2, #fce7e7);
        border: 2px solid var(--red-300);
    }

    .stat-icon svg {
        width: 2.25rem;
        height: 2.25rem;
        transition: transform 0.3s ease;
    }

    .stat-card:hover .stat-icon svg {
        transform: scale(1.1);
    }

    .stat-icon.green svg {
        color: var(--green-600);
    }

    .stat-icon.blue svg {
        color: var(--blue-600);
    }

    .stat-icon.red svg {
        color: var(--red-600);
    }

    .stat-number {
        font-size: 2.75rem;
        font-weight: 800;
        margin: 0 0 0.5rem 0;
        background: linear-gradient(135deg, var(--gray-800), var(--gray-600));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        transition: all 0.3s ease;
    }

    .stat-number.green {
        background: linear-gradient(135deg, var(--green-700), var(--green-500));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        /* Fallback for browsers that don't support background-clip */
        color: var(--green-700);
    }

    .stat-number.blue {
        background: linear-gradient(135deg, var(--blue-700), var(--blue-500));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        /* Fallback for browsers that don't support background-clip */
        color: var(--blue-700);
    }

    .stat-number.red {
        background: linear-gradient(135deg, var(--red-600), var(--red-400));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        /* Fallback for browsers that don't support background-clip */
        color: var(--red-600);
    }

    .stat-card:hover .stat-number {
        transform: scale(1.05);
    }

    .stat-label {
        font-size: 0.95rem;
        color: var(--gray-700);
        margin: 0;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: color 0.3s ease;
    }

    .stat-card:hover .stat-label {
        color: var(--gray-800);
    }

    /* Desktop Optimizations */
    @media (min-width: 1024px) {
        .main-content-grid {
            grid-template-columns: 300px 1fr;
            gap: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .tree-view {
            position: sticky;
            top: 2rem;
            max-height: calc(100vh - 4rem);
            overflow-y: auto;
        }
    }

    /* Tablet Responsive */
    @media (max-width: 1023px) and (min-width: 769px) {
        .main-content-grid {
            grid-template-columns: 250px 1fr;
            gap: 1.5rem;
        }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .header-title-wrapper {
            flex-direction: column;
            gap: 1rem;
        }

        .stats-container {
            grid-template-columns: 1fr;
            gap: 1rem;
            max-width: 400px;
        }

        .stat-card {
            padding: 1.5rem 1rem;
        }

        .stat-number {
            font-size: 2.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
        }

        .main-content-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }

    /* Project Cards Full Width */
    .project-cards-container {
        width: 100%;
        min-width: 0; /* Allow shrinking */
    }

    .project-card-wrapper {
        width: 100%;
        max-width: none;
    }

    .main-content-grid .user-card {
        width: 100%;
        max-width: none;
        margin: 0;
    }

    /* Ensure grid takes full available space */
    .main-content-grid {
        width: 100%;
        display: grid;
    }

    @media (max-width: 640px) {
        .stats-container {
            padding: 0 0.5rem;
        }

        .stat-card {
            padding: 1.25rem 1rem;
        }

        .stat-icon {
            padding: 0.875rem;
        }

        .stat-icon svg {
            width: 2rem;
            height: 2rem;
        }

        .stat-number {
            font-size: 2rem;
        }

        .stat-label {
            font-size: 0.8rem;
        }
    }

    .filters-card {
        background: #ffffff;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-xl);
        padding: 1.25rem;
        margin: 0 0 1rem;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        width: 100%;
    }

    .filters-card.collapsed .filter-grid,
    .filters-card.collapsed .filters-actions,
    .filters-card.collapsed .filter-badges {
        display: none;
    }

    .filters-card.collapsed {
        padding-bottom: 0.75rem;
    }

    .filters-collapse-btn {
        margin-left: auto;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: var(--gray-100);
        color: var(--gray-700);
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-lg);
        padding: 0.35rem 0.65rem;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s;
    }

    .filters-collapse-btn:hover {
        background: var(--gray-200);
        border-color: var(--gray-300);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.75rem;
        margin-top: 0.75rem;
    }

    .filter-grid label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--gray-600);
        margin-bottom: 0.35rem;
        display: block;
    }

    .filter-grid input,
    .filter-grid select {
        width: 100%;
        border-radius: 0.75rem;
        border: 1px solid var(--gray-200);
        padding: 0.65rem 0.85rem;
        font-size: 0.9rem;
        color: var(--gray-800);
        background: var(--gray-50);
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .filter-grid input:focus,
    .filter-grid select:focus {
        border-color: var(--green-400);
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15);
        outline: none;
        background: #fff;
    }

    .filters-actions {
        display: flex;
        align-items: stretch;
        gap: 0.5rem;
        margin-top: 1.25rem;
        flex-wrap: wrap;
    }

    .filters-actions button,
    .filters-actions a {
        border-radius: var(--radius-lg);
        padding: 0.75rem 1.25rem;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .filters-actions button.apply-btn {
        background: linear-gradient(135deg, var(--green-500), var(--green-600));
        color: white;
        box-shadow: 0 8px 24px rgba(34, 197, 94, 0.25);
    }

    .filters-actions button.apply-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(34, 197, 94, 0.35);
    }

    .filters-actions a.reset-btn {
        background: var(--gray-100);
        color: var(--gray-700);
        text-decoration: none;
    }

    .filters-actions a.reset-btn:hover {
        background: var(--gray-200);
        color: var(--gray-900);
    }

    .filter-badges {
        margin-top: 1rem;
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .filter-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.65rem;
        border-radius: 999px;
        background: var(--green-50);
        color: var(--green-700);
        font-size: 0.8rem;
        border: 1px solid var(--green-200);
    }
</style>

    <div class="user-container">
        <!-- Page Header -->
        <div class="header-content">
            <div class="header-title-wrapper">
                <svg class="project-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                </svg>
                <div class="project-title-section">
                    <h1>{{ __('common.all_projects') }}</h1>
                </div>
            </div>

            @php
                // Genel istatistikler
                $totalProjects = $projects->count();
                $totalSuggestions = $projects->sum(function($project) { return $project->suggestions->count(); });
                $totalLikes = $projects->sum(function($project) {
                    return $project->suggestions->sum(function($suggestion) {
                        return $suggestion->likes->count();
                    });
                });
            @endphp

            @if($totalProjects > 0)
            <!-- Statistics Section -->
            <div class="stats-section">
                <div class="stats-header">
                    <h2 class="stats-title">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width: 1.5rem; height: 1.5rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                        </svg>
                        {{ __('common.general_statistics') }}
                    </h2>
                </div>

                <div class="stats-container">
                    <!-- Total Projects Card -->
                    <div class="stat-card">
                        <div class="stat-icon-wrapper">
                            <div class="stat-icon green">
                                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="stat-number green">{{ $totalProjects }}</h3>
                        <p class="stat-label">{{ __('common.total_projects') }}</p>
                    </div>

                    <!-- Total Suggestions Card -->
                    <div class="stat-card">
                        <div class="stat-icon-wrapper">
                            <div class="stat-icon blue">
                                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="stat-number blue">{{ $totalSuggestions }}</h3>
                        <p class="stat-label">{{ __('common.total_suggestions') }}</p>
                    </div>

                    <!-- Total Likes Card -->
                    <div class="stat-card">
                        <div class="stat-icon-wrapper">
                            <div class="stat-icon red">
                                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="stat-number red">{{ $totalLikes }}</h3>
                        <p class="stat-label">{{ __('common.total_likes') }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>


<div class="section-padding">
    <div class="projects-wide-container">
        @php
            $selectedDistrict = $filterValues['district'] ?? null;
            $neighborhoodOptions = $selectedDistrict ? (config('istanbul_neighborhoods')[$selectedDistrict] ?? []) : [];
            $activeFilters = collect($filterValues)->filter(fn ($value) => filled($value));
            $activeFilterCount = $activeFilters->count();
            $filterLabelMap = [
                'search' => __('common.search'),
                'status' => __('common.status'),
                'category_id' => __('common.project_category'),
                'creator_type' => __('common.creator_type'),
                'district' => __('common.district'),
                'neighborhood' => __('common.neighborhood'),
                'start_date' => __('common.start_date'),
                'end_date' => __('common.end_date'),
                'min_budget' => __('common.min_budget'),
                'max_budget' => __('common.max_budget'),
            ];
        @endphp

        <div class="d-grid main-content-grid" style="grid-template-columns: 300px 1fr; gap: 2rem;">
            <!-- Sol Taraf: Tree View -->
            <div>
                <div id="user-filter-panel" class="filters-card">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <svg style="width: 1rem; height: 1rem; color: var(--green-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h18M4.5 8.25h15L13.5 15v5.25l-3-1.5V15L4.5 8.25z"/>
                        </svg>
                        <h3 style="font-size: 1rem; font-weight: 700; color: var(--gray-900); margin: 0;">
                            {{ __('common.filters_button') }}
                        </h3>
                        @if($activeFilterCount)
                            <span class="filter-badge">{{ $activeFilterCount }}</span>
                        @endif
                        <button type="button" id="filters-collapse-btn" class="filters-collapse-btn">
                            <span class="collapse-text">{{ __('common.clear') }}</span>
                            <svg class="collapse-icon" style="width: 0.85rem; height: 0.85rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>
                    </div>
                    <form method="GET" action="{{ route('user.projects') }}">
                        <div class="filter-grid">
                            <div class="filter-field">
                                <label for="search">{{ __('common.title') }}</label>
                                <div class="input-with-icon">
                                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0a7 7 0 10-9.9-9.9 7 7 0 009.9 9.9z"/>
                                    </svg>
                                    <input type="text" id="search" name="search" value="{{ $filterValues['search'] ?? '' }}" placeholder="{{ __('common.search') }}">
                                </div>
                            </div>
                            <div class="filter-field">
                                <label for="status">{{ __('common.status') }}</label>
                                <div class="input-with-icon">
                                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l3.75 3.75 11.25-11.25"/>
                                    </svg>
                                    <select id="status" name="status">
                                        <option value="">{{ __('common.select_option') }}</option>
                                        @foreach($statusOptions as $value => $label)
                                            <option value="{{ $value }}" @selected(($filterValues['status'] ?? '') === $value)>{{ __($label) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="filter-field">
                                <label for="category_id">{{ __('common.project_category') }}</label>
                                <div class="input-with-icon">
                                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5h18M3 12h18M3 16.5h18"/>
                                    </svg>
                                    <select id="category_id" name="category_id">
                                        <option value="">{{ __('common.select_option') }}</option>
                                        @foreach($filterCategories as $category)
                                            <option value="{{ $category->id }}" @selected(($filterValues['category_id'] ?? '') == $category->id)>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="filter-field">
                                <label for="creator_type">{{ __('common.creator_type') }}</label>
                                <div class="input-with-icon">
                                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 0115 0"/>
                                    </svg>
                                    <select id="creator_type" name="creator_type">
                                        <option value="">{{ __('common.select_option') }}</option>
                                        <option value="with_user" @selected(($filterValues['creator_type'] ?? '') === 'with_user')>{{ __('common.user_assigned') }}</option>
                                        <option value="not_assigned" @selected(($filterValues['creator_type'] ?? '') === 'not_assigned')>{{ __('common.not_assigned') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="filter-field">
                                <label for="district-filter">{{ __('common.district') }}</label>
                                <div class="input-with-icon">
                                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21c-4.8-3.6-7.2-7.2-7.2-10.8a7.2 7.2 0 1114.4 0c0 3.6-2.4 7.2-7.2 10.8z"/>
                                        <circle cx="12" cy="10.2" r="2.4"/>
                                    </svg>
                                    <select id="district-filter" name="district">
                                        <option value="">{{ __('common.select_option') }}</option>
                                        @foreach($districts as $district)
                                            <option value="{{ $district }}" @selected(($filterValues['district'] ?? '') === $district)>{{ $district }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="filter-field">
                                <label for="neighborhood-filter">{{ __('common.neighborhood') }}</label>
                                <div class="input-with-icon">
                                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 9.75l7.5-6 7.5 6v9.75A2.25 2.25 0 0117.25 21h-10.5A2.25 2.25 0 013 18.75V9.75z"/>
                                    </svg>
                                    <select id="neighborhood-filter" name="neighborhood">
                                        <option value="">{{ __('common.select_option') }}</option>
                                        @foreach($neighborhoodOptions as $neighborhood)
                                            <option value="{{ $neighborhood }}" @selected(($filterValues['neighborhood'] ?? '') === $neighborhood)>{{ $neighborhood }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="filter-field">
                                <label for="start_date">{{ __('common.start_date') }}</label>
                                <div class="input-with-icon">
                                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 8.25h18M4.5 21h15a1.5 1.5 0 001.5-1.5V6.75A1.5 1.5 0 0019.5 5.25H4.5A1.5 1.5 0 003 6.75V19.5A1.5 1.5 0 004.5 21z"/>
                                    </svg>
                                    <input type="date" id="start_date" name="start_date" value="{{ $filterValues['start_date'] ?? '' }}">
                                </div>
                            </div>
                            <div class="filter-field">
                                <label for="end_date">{{ __('common.end_date') }}</label>
                                <div class="input-with-icon">
                                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 8.25h18M4.5 21h15a1.5 1.5 0 001.5-1.5V6.75A1.5 1.5 0 0019.5 5.25H4.5A1.5 1.5 0 003 6.75V19.5A1.5 1.5 0 004.5 21z"/>
                                    </svg>
                                    <input type="date" id="end_date" name="end_date" value="{{ $filterValues['end_date'] ?? '' }}">
                                </div>
                            </div>
                            <div class="filter-field">
                                <label for="min_budget">{{ __('common.min_budget') }}</label>
                                <div class="input-with-icon">
                                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/>
                                    </svg>
                                    <input type="number" step="0.01" id="min_budget" name="min_budget" value="{{ $filterValues['min_budget'] ?? '' }}">
                                </div>
                            </div>
                            <div class="filter-field">
                                <label for="max_budget">{{ __('common.max_budget') }}</label>
                                <div class="input-with-icon">
                                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/>
                                    </svg>
                                    <input type="number" step="0.01" id="max_budget" name="max_budget" value="{{ $filterValues['max_budget'] ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="filters-actions">
                            <button type="submit" class="apply-btn" style="width: 100%; text-align: center;">{{ __('common.filters_button') }}</button>
                            @if($activeFilters->isNotEmpty())
                                <a href="{{ route('user.projects') }}" class="reset-btn" style="width: 100%; text-align: center;">{{ __('common.clear') }}</a>
                            @endif
                        </div>
                    </form>
                    @if($activeFilters->isNotEmpty())
                        <div class="filter-badges">
                            @foreach($activeFilters as $key => $value)
                                <span class="filter-badge">
                                    {{ $filterLabelMap[$key] ?? ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="tree-view">
                    <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                        <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem; color: var(--green-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                        </svg>
                        <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-900); margin: 0;">{{ __('common.project_list') }}</h3>
                    </div>

                    <!-- Info about voting system -->
                    <div style="background: var(--green-50); border: 1px solid var(--green-200); border-radius: var(--radius-md); padding: 0.75rem; margin-bottom: 1rem;">
                        <div style="display: flex; align-items: start; gap: 0.5rem;">
                            <svg style="width: 1rem; height: 1rem; color: var(--green-600); margin-top: 0.125rem; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                            </svg>
                            <div>
                                <p style="font-size: 0.75rem; color: var(--green-700); margin: 0; line-height: 1.4; font-weight: 500;">
                                    <strong>{{ __('common.voting_system') }}:</strong> {{ __('common.voting_system_description') }}
                                </p>
                                <p style="font-size: 0.7rem; color: var(--green-600); margin: 0.25rem 0 0; line-height: 1.3;">
                                    {{ __('common.voting_system_help') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div style="space-y: 0.5rem;">
                        @foreach($projects as $project)
                        <div class="tree-project-wrapper" data-title="{{ Str::lower($project->name) }}" style="border-bottom: 1px solid var(--green-100); padding-bottom: 0.5rem;">
                            <!-- Project Node -->
                            <a href="{{ route('user.project.suggestions', $project->id) }}" class="tree-project"
                                 data-project-id="{{ $project->id }}"
                                 style="display: flex; align-items: center; padding: 0.5rem; border-radius: 0.5rem; transition: background-color 0.2s; text-decoration: none;">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem; color: var(--green-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                                </svg>
                                <span style="font-size: 0.875rem; font-weight: 500; color: var(--gray-900); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ Str::limit($project->name, 25) }}</span>
                                <span style="margin-left: auto; font-size: 0.75rem; color: var(--gray-500);">({{ $project->suggestions->count() }})</span>
                            </a>

                            <!-- Suggestions -->
                            @if($project->suggestions->count() > 0)
                            <div class="tree-suggestions">
                                @foreach($project->suggestions as $suggestion)
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
            <div class="project-cards-container">
                @if($projects->count() > 0)
                <div class="d-flex" style="flex-direction: column; gap: 2rem; width: 100%;">
                    @foreach($projects as $project)
                    <div id="project-{{ $project->id }}" class="user-card" style="overflow: hidden; position: relative; min-height: 200px;">
                        <!-- Project Background Image -->
                        @if($project->getFirstMediaUrl('images'))
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 1;">
                            <img src="{{ $project->getFirstMediaUrl('images') }}"
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
                            <!-- Tüm Önerileri Göster Butonu - Sağ Üst -->
                            <a href="{{ route('user.project.suggestions', $project->id) }}"
                               style="position: absolute; top: 1rem; right: 1rem;
                                      background: rgba(255,255,255,0.2);
                                      border: 1px solid rgba(255,255,255,0.4);
                                      color: white;
                                      padding: 0.5rem 1rem;
                                      border-radius: 0.5rem;
                                      font-size: 0.75rem;
                                      font-weight: 500;
                                      text-decoration: none;
                                      transition: all 0.3s;
                                      backdrop-filter: blur(4px);
                                      display: flex;
                                      align-items: center;
                                      gap: 0.25rem;
                                      text-shadow: 0 1px 2px rgba(0,0,0,0.5);
                                      box-shadow: 0 2px 4px rgba(0,0,0,0.2);"
                               onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.3)';"
                               onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.2)';">
                                <svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
                                </svg>
                                <span>{{ __('common.all_suggestions') }}</span>
                            </a>

                            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                                <svg style="width: 1.5rem; height: 1.5rem; margin-right: 0.75rem; color: rgba(255,255,255,0.9);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                                </svg>
                                <a href="{{ route('user.project.suggestions', $project->id) }}" style="text-decoration: none;">
                                    <h2 style="font-size: 1.75rem; font-weight: 700; color: white; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.5); transition: color 0.2s;">{{ $project->name }}</h2>
                                </a>
                            </div>

                            @if($project->createdBy)
                            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem; color: rgba(255,255,255,0.8);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                                <span style="font-size: 0.875rem; color: rgba(255,255,255,0.9); text-shadow: 0 1px 2px rgba(0,0,0,0.5);">
                                    {{ __('common.project_manager') }}: {{ $project->createdBy->name }}
                                </span>
                            </div>
                            @endif

                            <p style="font-size: 1rem; color: rgba(255,255,255,0.9); margin-bottom: 1rem; text-shadow: 0 1px 2px rgba(0,0,0,0.5); line-height: 1.5;">
                                <svg style="width: 1rem; height: 1rem; display: inline; margin-right: 0.5rem; color: rgba(255,255,255,0.8);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round"></circle>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25h.008v.008H12V8.25zm0 2.25v4.5" />
                                </svg>
                                {{ $project->description }}
                            </p>

                            <!-- Project Meta (stacked) -->
                            <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; color: rgba(255,255,255,0.9); font-size: 0.875rem;">
                                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
                                    </svg>
                                    {{ $project->suggestions->count() }} {{ __('common.suggestion') }}
                                </div>

                                @if($project->formatted_end_date)
                                <div style="display: flex; align-items: center; gap: 0.5rem; color: rgba(255,255,255,0.9); font-size: 0.875rem;">
                                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                    {{ __('common.project_end') }}: {{ $project->formatted_end_date }}
                                </div>
                                @endif

                                @if($project->getRemainingTime())
                                    @php
                                        $remainingTime = $project->getRemainingTime();
                                        $isExpired = $project->isExpired();
                                    @endphp
                                    <div style="display: flex; align-items: center; gap: 0.5rem; color: {{ $isExpired ? 'rgba(239, 68, 68, 0.9)' : 'rgba(34, 197, 94, 0.9)' }}; font-size: 0.875rem; font-weight: 600;">
                                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                        @if($isExpired)
                                            <span>{{ __('common.expired') }} - {{ __('common.project_expired_message') }}</span>
                                        @elseif($remainingTime)
                                            <span>{{ __('common.remaining') }}: {{ $remainingTime['formatted'] }}</span>
                                        @else
                                            <span>{{ __('common.unlimited') }}</span>
                                        @endif
                                    </div>
                                @endif

                                @if($project->district)
                                <div style="display: flex; align-items: center; gap: 0.5rem; color: rgba(255,255,255,0.9); font-size: 0.875rem;">
                                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                                    </svg>
                                    {{ $project->district }}, {{ $project->neighborhood }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Suggestions -->
                        @if($project->suggestions->count() > 0)
                        <div style="position: relative; z-index: 3; padding: 0 2rem 2rem 2rem;">
                            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                                <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem; color: white;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
                                </svg>
                                <h3 style="font-size: 1.125rem; font-weight: 600; color: white; margin: 0; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">{{ __('common.suggestions') }} ({{ $project->suggestions->count() }})</h3>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                @foreach($project->suggestions as $suggestion)
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

                                            @if($suggestion->createdBy)
                                            <div style="display: flex; align-items: center; margin-bottom: 0.75rem;">
                                                <svg style="width: 0.875rem; height: 0.875rem; margin-right: 0.5rem; color: rgba(255,255,255,0.8);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                </svg>
                                                <span style="font-size: 0.75rem; color: rgba(255,255,255,0.8); text-shadow: 0 1px 2px rgba(0,0,0,0.5);">
                                                    {{ $suggestion->createdBy->name }}
                                                </span>
                                            </div>
                                            @endif

                                            <p style="font-size: 0.875rem; color: rgba(255,255,255,0.9); margin-bottom: 0.5rem; text-shadow: 0 1px 2px rgba(0,0,0,0.5); line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                <svg style="width: 0.875rem; height: 0.875rem; display: inline-block; vertical-align: middle; margin-right: 0.5rem; color: rgba(255,255,255,0.8);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                                    <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round"></circle>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25h.008v.008H12V8.25zm0 2.25v4.5" />
                                                </svg>
                                                {{ Str::limit($suggestion->description, 80) }}
                                            </p>

                                            <div style="display: flex; align-items: center; gap: 0.5rem; color: rgba(255,255,255,0.8); font-size: 0.75rem; margin-bottom: 0.5rem;">
                                                <svg style="width: 0.75rem; height: 0.75rem; color: rgba(255,255,255,0.8);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.627 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
                                                </svg>
                                                <span>{{ $suggestion->comments->count() }} {{ __('common.comment') }}</span>
                                            </div>
                                        </div>

                                        <!-- Suggestion Actions -->
                                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
                                            <div style="display: flex; align-items: center; gap: 1rem;">
                                                <!-- Like Button -->
                                                @php
                                                    $isProjectExpired = $project->isExpired();
                                                @endphp
                                                <button onclick="{{ $isProjectExpired ? 'showExpiredMessage()' : 'toggleLike(' . $suggestion->id . ')' }}"
                                                        class="btn-like {{ Auth::check() && $suggestion->likes->where('user_id', Auth::id())->count() > 0 ? 'liked' : '' }} {{ $isProjectExpired ? 'expired' : '' }}"
                                                        data-suggestion-id="{{ $suggestion->id }}"
                                                        data-project-id="{{ $project->id }}"
                                                        data-category="{{ $suggestion->category_id ?? 'default' }}"
                                                        data-expired="{{ $isProjectExpired ? 'true' : 'false' }}"
                                                        title="{{ $isProjectExpired ? __('common.project_expired_message') : __('common.suggestion_like_tooltip') }}"
                                                        {{ $isProjectExpired ? 'disabled' : '' }}>

                                                    <svg class="like-icon" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                                    </svg>
                                                    <span class="like-count">{{ $suggestion->likes->count() }}</span>
                                                </button>

                                                <!-- Comments Count -->

                                            </div>

                                            <!-- Details Button -->
                                            <a href="{{ route('user.suggestion.detail', $suggestion->id) }}"
                                               style="color: rgba(255,255,255,0.9); background: var(--green-700); padding: 0.375rem 0.75rem; border-radius: var(--radius-md); font-size: 0.75rem; font-weight: 500; text-decoration: none; transition: all 0.2s; backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.5); display: flex; align-items: center; gap: 0.25rem;">
                                                <svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                                </svg>
                                                {{ __('common.detail') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
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
                @else
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
</div>
</div>



<!-- JavaScript for interactions -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const neighborhoodsMap = @json(config('istanbul_neighborhoods', []));
        const districtSelect = document.getElementById('district-filter');
        const neighborhoodSelect = document.getElementById('neighborhood-filter');
        const projectWrappers = document.querySelectorAll('.tree-project-wrapper');
        const filterCard = document.getElementById('user-filter-panel');
        const collapseBtn = document.getElementById('filters-collapse-btn');

        if (districtSelect && neighborhoodSelect) {
            districtSelect.addEventListener('change', function () {
                const value = this.value;
                const options = neighborhoodsMap[value] || [];
                neighborhoodSelect.innerHTML = '<option value="">{{ __('common.select_option') }}</option>';

                options.forEach(option => {
                    const opt = document.createElement('option');
                    opt.value = option;
                    opt.textContent = option;
                    neighborhoodSelect.appendChild(opt);
                });
            });
        }

        if (filterCard && collapseBtn) {
            const collapseIcon = collapseBtn.querySelector('.collapse-icon');
            const collapseText = collapseBtn.querySelector('.collapse-text');

            const setCollapsedState = (collapsed) => {
                filterCard.classList.toggle('collapsed', collapsed);
                if (collapsed) {
                    collapseText.textContent = '{{ __('common.filters_button') }}';
                    collapseIcon.style.transform = 'rotate(180deg)';
                } else {
                    collapseText.textContent = '{{ __('common.clear') }}';
                    collapseIcon.style.transform = 'rotate(0deg)';
                }
            };

            collapseBtn.addEventListener('click', () => {
                const collapsed = !filterCard.classList.contains('collapsed');
                setCollapsedState(collapsed);
            });
        }

    });
</script>

<script>
// Set up CSRF token for AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
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



// Show message function with enhanced styling for like system
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

// Add CSS for message animations and like buttons
if (!document.getElementById('message-styles')) {
    const style = document.createElement('style');
    style.id = 'message-styles';
    style.textContent = `
        /* Like Button Styles */
        .btn-like {
            background: rgba(255,255,255,0.15) !important;
            border: 1px solid rgba(255,255,255,0.3) !important;
            color: white !important;
            padding: 0.375rem 0.75rem !important;
            border-radius: var(--radius-md) !important;
            font-size: 0.75rem !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.25rem !important;
            transition: all 0.2s !important;
            backdrop-filter: blur(4px) !important;
            cursor: pointer !important;
            font-weight: 600 !important;
        }

        .btn-like-large {
            padding: 0.75rem 1.25rem !important;
            font-size: 0.875rem !important;
            gap: 0.5rem !important;
            backdrop-filter: blur(10px) !important;
            box-shadow: var(--shadow-md) !important;
        }

        .btn-like.liked {
            background: #ef4444 !important;
            border-color: #dc2626 !important;
        }

        .btn-like:hover:not(.liked) {
            background: rgba(255,255,255,0.25) !important;
            border-color: rgba(255,255,255,0.5) !important;
        }

        .btn-like.liked:hover {
            background: #dc2626 !important;
            border-color: #b91c1c !important;
        }

        .btn-like.expired {
            background: rgba(107, 114, 128, 0.5) !important;
            border-color: rgba(107, 114, 128, 0.3) !important;
            color: rgba(255, 255, 255, 0.5) !important;
            cursor: not-allowed !important;
            opacity: 0.6 !important;
        }

        .btn-like.expired:hover {
            background: rgba(107, 114, 128, 0.5) !important;
            border-color: rgba(107, 114, 128, 0.3) !important;
            transform: none !important;
        }

        .btn-like .like-icon {
            width: 0.875rem !important;
            height: 0.875rem !important;
            fill: none !important;
        }

        .btn-like .like-icon-large {
            width: 1rem !important;
            height: 1rem !important;
        }

        .btn-like.liked .like-icon {
            fill: currentColor !important;
        }

        /* Message Animations */
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

// Responsive grid adjustment for mobile
function adjustLayout() {
    const container = document.querySelector('.main-content-grid');
    if (container && window.innerWidth < 768) {
        container.style.gridTemplateColumns = '1fr';
        container.style.gap = '1rem';
    } else if (container && window.innerWidth < 1024) {
        container.style.gridTemplateColumns = '250px 1fr';
        container.style.gap = '1.5rem';
    } else if (container) {
        container.style.gridTemplateColumns = '300px 1fr';
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

// Add hover effects for action buttons
document.addEventListener('DOMContentLoaded', function() {
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



// Add hover effects for "show more" buttons
document.addEventListener('DOMContentLoaded', function() {
    const showMoreButtons = document.querySelectorAll('.btn-show-more');
    showMoreButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.background = 'rgba(255,255,255,0.3)';
            this.style.borderColor = 'rgba(255,255,255,0.6)';
            this.style.transform = 'translateY(-2px)';
        });

        button.addEventListener('mouseleave', function() {
            this.style.background = 'rgba(255,255,255,0.2)';
            this.style.borderColor = 'rgba(255,255,255,0.4)';
            this.style.transform = 'translateY(0)';
        });
    });
});

// Show expired message for expired projects
function showExpiredMessage() {
    showMessage('Bu projenin süresi dolmuştur. Artık beğeni yapılamaz.', 'error');
}

// Update the toggleLike function to handle expired projects
function toggleLike(suggestionId) {
    // Check if button is expired first
    const clickedButton = document.querySelector(`[data-suggestion-id="${suggestionId}"]`);
    if (clickedButton && clickedButton.getAttribute('data-expired') === 'true') {
        showExpiredMessage();
        return;
    }

    @guest
        showMessage('{{ __('common.login_required_like') }}', 'error');
        setTimeout(() => {
            window.location.href = '{{ route('user.login') }}';
        }, 2000);
        return;
    @endguest

    const likeCount = clickedButton.querySelector('.like-count');

    // Get current suggestion's category from the data attributes
    const suggestionCategory = clickedButton.getAttribute('data-category') || 'default';

    // Find all buttons in the same category (radio button behavior - across all projects)
    const allButtonsInCategory = document.querySelectorAll(`[data-category="${suggestionCategory}"]`);

    // Disable all buttons in this category during request
    allButtonsInCategory.forEach(btn => {
        // Don't disable expired buttons - just leave them as is
        if (btn.getAttribute('data-expired') !== 'true') {
            btn.disabled = true;
            btn.style.opacity = '0.7';
            btn.style.pointerEvents = 'none';
        }
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
                // Skip expired buttons
                if (btn.getAttribute('data-expired') === 'true') {
                    return;
                }

                btn.classList.remove('liked');
                const btnSuggestionId = btn.getAttribute('data-suggestion-id');

                // Reset heart icon to outline for all buttons in category
                const heartIcon = btn.querySelector('.like-icon');
                if (heartIcon) {
                    heartIcon.style.fill = 'none';
                }

                // Update like counts from server response if available
                if (response.all_likes && response.all_likes[btnSuggestionId] !== undefined) {
                    const btnLikeCount = btn.querySelector('.like-count');
                    if (btnLikeCount) {
                        btnLikeCount.textContent = response.all_likes[btnSuggestionId];
                    }
                }
            });

            // Update clicked button's like count
            likeCount.textContent = response.likes_count;

            // Update clicked button appearance if it's now liked
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
                    showMessage('{{ __("common.suggestion_liked") }}', 'success');
                }
            } else {
                clickedButton.classList.remove('liked');

                // Reset heart icon to outline
                const heartIcon = clickedButton.querySelector('.like-icon');
                if (heartIcon) {
                    heartIcon.style.fill = 'none';
                }

                showMessage('{{ __('common.like_removed') }}', 'info');
            }
        },
        error: function(xhr) {
            let message = 'Bir hata oluştu.';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                message = xhr.responseJSON.error;
            }

            // Handle expired project error specifically
            if (xhr.responseJSON && xhr.responseJSON.expired) {
                // Mark button as expired and update its appearance
                clickedButton.setAttribute('data-expired', 'true');
                clickedButton.classList.add('expired');
                clickedButton.disabled = true;
                clickedButton.onclick = function() { showExpiredMessage(); };
            }

            showMessage(message, 'error');
        },
        complete: function() {
            // Re-enable all non-expired buttons in the category
            allButtonsInCategory.forEach(btn => {
                if (btn.getAttribute('data-expired') !== 'true') {
                    btn.disabled = false;
                    btn.style.opacity = '1';
                    btn.style.pointerEvents = 'auto';
                }
            });
        }
    });
}

// Call on load and resize
window.addEventListener('load', adjustLayout);
window.addEventListener('resize', adjustLayout);
</script>
    </div>
</div>
@endsection
