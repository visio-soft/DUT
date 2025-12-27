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
        padding: 1rem;
        margin: 0 0 1rem;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        width: 100%;
        overflow: hidden;
        transition: padding 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
    }

    .filters-card.collapsed {
        padding-bottom: 0.75rem;
    }

    .filters-collapse-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        width: 100%;
        background: transparent;
        color: var(--gray-900);
        border: none;
        border-radius: var(--radius-lg);
        padding: 0.35rem 0;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: color 0.2s ease;
        text-align: left;
    }

    .filters-collapse-btn:hover {
        color: var(--gray-800);
    }

    .filters-header-info {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filters-header-info h3 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0;
    }

    .collapse-icon-circle {
        margin-left: auto;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 999px;
        background: var(--gray-100);
        color: var(--gray-700);
        border: 1px solid var(--gray-200);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s, border-color 0.2s, transform 0.3s ease;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.08);
    }

    .filters-collapse-btn:hover .collapse-icon-circle {
        background: var(--gray-200);
        border-color: var(--gray-300);
    }

    .filters-card.collapsed .collapse-icon-circle {
        transform: rotate(180deg);
    }

    .collapse-icon {
        width: 0.9rem;
        height: 0.9rem;
    }

    .filters-content {
        overflow: hidden;
        max-height: 2000px;
        opacity: 1;
        transform: translateY(0);
        transition: max-height 0.35s ease, opacity 0.25s ease, transform 0.25s ease;
    }

    .filters-card.collapsed .filters-content {
        max-height: 0;
        opacity: 0;
        transform: translateY(-6px);
        pointer-events: none;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.6rem;
        margin-top: 0.75rem;
    }

    .filter-row {
        display: grid;
        gap: 0.75rem;
    }

    .filter-row.two-cols {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    @media (max-width: 640px) {
        .filter-row.two-cols {
            grid-template-columns: 1fr;
        }
    }

    .filter-grid label {
        font-size: 0.8rem;
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
        padding: 0.55rem 0.75rem;
        font-size: 0.85rem;
        color: var(--gray-800);
        background: var(--gray-50);
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .compact-filter-input {
        padding: 0.4rem 0.65rem;
        font-size: 0.8rem;
        line-height: 1.2;
    }

    .filters-card .input-with-icon {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .filters-card .input-with-icon svg {
        width: 0.95rem;
        height: 0.95rem;
        flex-shrink: 0;
    }

    .filters-card .input-with-icon input,
    .filters-card .input-with-icon select {
        flex: 1;
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
        padding: 0.65rem 1rem;
        border: none;
        font-weight: 600;
        font-size: 0.9rem;
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
                'district' => __('common.district'),
                'neighborhood' => __('common.neighborhood'),
                'start_date' => __('common.start_date'),
                'end_date' => __('common.end_date'),
                'min_budget' => __('common.min_budget'),
                'max_budget' => __('common.max_budget'),
            ];
            $startCollapsed = $activeFilters->isEmpty();
        @endphp

        <div class="d-grid main-content-grid" style="grid-template-columns: 300px 1fr; gap: 2rem;">
            <!-- Sol Taraf: Tree View -->
            <div>
                <div id="filter-card" class="filters-card">
                    <button type="button" id="collapse-btn" class="filters-collapse-btn">
                        <div class="filters-header-info">
                            <svg style="width: 1rem; height: 1rem; color: var(--green-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h18M4.5 8.25h15L13.5 15v5.25l-3-1.5V15L4.5 8.25z"/>
                            </svg>
                            <h3>{{ __('common.filters_button') }}</h3>
                            @if($activeFilterCount)
                                <span class="filter-badge">{{ $activeFilterCount }}</span>
                            @endif
                        </div>
                        <span class="collapse-icon-circle">
                            <svg class="collapse-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                            </svg>
                        </span>
                    </button>
                    <div id="filter-content" class="filters-content">
                        <form id="projects-filters-form" method="GET" action="{{ route('user.projects') }}">
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
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
                                        </svg>
                                        <select id="category_id" name="category_id">
                                            <option value="">{{ __('common.select_option') }}</option>
                                            @foreach($categories as $id => $name)
                                                <option value="{{ $id }}" @selected(($filterValues['category_id'] ?? '') == $id)>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="filter-field">
                                    <label for="country-filter">{{ __('common.country') }}</label>
                                    <div class="input-with-icon">
                                         <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                                        </svg>
                                        <select id="country-filter" name="country" onchange="this.form.submit()">
                                            <option value="">{{ __('common.select_option') }}</option>
                                            @foreach($countries as $name)
                                                <option value="{{ $name }}" @selected(($filterValues['country'] ?? '') === $name)>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="filter-field" id="city-wrapper" style="{{ !empty($cities) || !empty($filterValues['city']) ? '' : 'display: none;' }}">
                                    <label for="city-filter">{{ __('common.city') }}</label>
                                    <div class="input-with-icon">
                                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                                        </svg>
                                        <select id="city-filter" name="city" onchange="this.form.submit()">
                                            <option value="">{{ __('common.select_option') }}</option>
                                            @if(!empty($cities))
                                                @foreach($cities as $name)
                                                    <option value="{{ $name }}" @selected(($filterValues['city'] ?? '') === $name)>{{ $name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="filter-field">
                                    <label for="start_date">{{ __('common.start_date') }}</label>
                                    <input class="compact-filter-input" type="date" id="start_date" name="start_date" value="{{ $filterValues['start_date'] ?? '' }}">
                                </div>
                                <div class="filter-field">
                                    <label for="end_date">{{ __('common.end_date') }}</label>
                                    <input class="compact-filter-input" type="date" id="end_date" name="end_date" value="{{ $filterValues['end_date'] ?? '' }}">
                                </div>
                                <div class="filter-field">
                                    <label for="min_budget">{{ __('common.min_budget') }}</label>
                                    <input class="compact-filter-input" type="number" step="0.01" id="min_budget" name="min_budget" value="{{ $filterValues['min_budget'] ?? '' }}">
                                </div>
                                <div class="filter-field">
                                    <label for="max_budget">{{ __('common.max_budget') }}</label>
                                    <input class="compact-filter-input" type="number" step="0.01" id="max_budget" name="max_budget" value="{{ $filterValues['max_budget'] ?? '' }}">
                                </div>
                            </div>
                            <div class="filters-actions">
                                <a href="{{ route('user.projects') }}" class="reset-btn">
                                    {{ __('common.clear_filters') }}
                                </a>
                                <button type="submit" class="apply-btn">
                                    {{ __('common.apply_filters') }}
                                </button>
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
                        <!-- Project Content -->
                        <div style="position: relative; z-index: 3; padding: 2rem; color: white;">
                            <!-- Header Actions Row -->
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; gap: 1rem;">
                                <!-- Status Badge (Left) -->
                                <div>
                                    @if($project->status === \App\Enums\SuggestionStatusEnum::OPEN)
                                    <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(34, 197, 94, 0.2); backdrop-filter: blur(4px); border: 1px solid rgba(255, 255, 255, 0.2); padding: 0.35rem 0.75rem; border-radius: 9999px; color: white; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                        <span style="width: 0.5rem; height: 0.5rem; background: #4ade80; border-radius: 50%; box-shadow: 0 0 8px #4ade80;"></span>
                                        {{ __('common.active') }}
                                    </div>
                                    @endif
                                </div>

                                <!-- Actions Actions (Right) -->
                                <!-- Actions Actions (Right) -->
                                <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; justify-content: flex-end;">
                                    @if($project->surveys->isNotEmpty())
                                        @php
                                            $activeSurvey = $project->surveys->first();
                                            $hasUnansweredSurveys = $project->surveys->contains(function ($survey) {
                                                return $survey->responses->isEmpty();
                                            });
                                        @endphp
                                    <a href="#"
                                       onclick="Livewire.dispatch('openSurveyModal', { surveyId: {{ $activeSurvey->id }} }); return false;"
                                       class="btn-survey {{ $hasUnansweredSurveys ? 'pending' : 'completed' }}"
                                       style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; 
                                              padding: 0.4rem 0.8rem; border-radius: 2rem; 
                                              font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; 
                                              transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                                              {{ $hasUnansweredSurveys 
                                                 ? 'background: linear-gradient(135deg, #1ABF6B 0%, #16a559 100%); border: 1px solid #16a559; color: white; box-shadow: 0 4px 12px rgba(26, 191, 107, 0.4); animation: pulse-green 2s infinite;' 
                                                 : 'background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.2); color: rgba(255, 255, 255, 0.9);' 
                                              }}"
                                       onmouseover="{{ $hasUnansweredSurveys 
                                            ? 'this.style.transform=\'translateY(-1px) scale(1.02)\'; this.style.boxShadow=\'0 6px 16px rgba(26, 191, 107, 0.5)\';' 
                                            : 'this.style.background=\'rgba(255, 255, 255, 0.25)\';' 
                                       }}"
                                       onmouseout="{{ $hasUnansweredSurveys 
                                            ? 'this.style.transform=\'translateY(0) scale(1)\'; this.style.boxShadow=\'0 4px 12px rgba(26, 191, 107, 0.4)\';' 
                                            : 'this.style.background=\'rgba(255, 255, 255, 0.15)\';' 
                                       }}">
                                        <svg style="width: 0.85rem; height: 0.85rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/>
                                        </svg>
                                        {{ $hasUnansweredSurveys ? __('common.join_survey') : __('common.survey') }}
                                    </a>
                                    @endif

                                    <a href="{{ route('user.project.suggestions', $project->id) }}"
                                       style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; 
                                              background: rgba(255, 255, 255, 0.2); 
                                              border: 1px solid rgba(255, 255, 255, 0.3); padding: 0.4rem 0.8rem; 
                                              border-radius: 2rem; color: white; font-size: 0.75rem; font-weight: 600; 
                                              text-transform: uppercase; letter-spacing: 0.05em; 
                                              transition: background 0.2s;"
                                       onmouseover="this.style.background='rgba(255, 255, 255, 0.3)'"
                                       onmouseout="this.style.background='rgba(255, 255, 255, 0.2)'">
                                        <svg style="width: 0.85rem; height: 0.85rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
                                        </svg>
                                        {{ __('common.all_suggestions') }}
                                    </a>
                                </div>
                            </div>

                            <!-- Project Title -->
                            <h2 style="font-size: 1.75rem; font-weight: 700; color: white; margin: 0 0 1rem 0; text-shadow: 0 2px 4px rgba(0,0,0,0.5); line-height: 1.2;">
                                {{ $project->name }}
                            </h2>

                            @if($project->createdBy)
                            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem; color: rgba(255,255,255,0.8);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
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
                                                        style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 600; background: {{ $isProjectExpired ? 'rgba(107, 114, 128, 0.5)' : 'rgba(239, 68, 68, 0.95)' }}; border: 2px solid {{ $isProjectExpired ? 'rgba(107, 114, 128, 0.3)' : '#dc2626' }}; border-radius: var(--radius-lg); color: {{ $isProjectExpired ? 'rgba(255, 255, 255, 0.5)' : 'white' }}; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.3); backdrop-filter: blur(8px); {{ $isProjectExpired ? 'cursor: not-allowed; opacity: 0.6;' : '' }}"
                                                        data-suggestion-id="{{ $suggestion->id }}"
                                                        data-project-id="{{ $project->id }}"
                                                        data-category="{{ $suggestion->category_id ?? 'default' }}"
                                                        data-expired="{{ $isProjectExpired ? 'true' : 'false' }}"
                                                        title="{{ $isProjectExpired ? __('common.project_expired_message') : __('common.suggestion_like_tooltip') }}"
                                                        {{ $isProjectExpired ? 'disabled' : '' }}
                                                        onmouseover="{{ $isProjectExpired ? '' : 'this.style.background=\'rgba(220, 38, 38, 0.95)\'; this.style.transform=\'translateY(-2px) scale(1.05)\'' }}"
                                                        onmouseout="{{ $isProjectExpired ? '' : 'this.style.background=\'rgba(239, 68, 68, 0.95)\'; this.style.transform=\'translateY(0) scale(1)\'' }}">

                                                    <svg class="like-icon" style="width: 1rem; height: 1rem; {{ Auth::check() && $suggestion->likes->where('user_id', Auth::id())->count() > 0 ? 'fill: currentColor;' : 'fill: none;' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                                    </svg>
                                                    <span>{{ __('common.like_button') }}</span>
                                                    <span class="like-count" style="background: rgba(255, 255, 255, 0.2); color: white; padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; min-width: 1.5rem; text-align: center;">{{ $suggestion->likes->count() }}</span>
                                                </button>

                                                <!-- Comments Count -->

                                            </div>

                                            <!-- Details Button -->
                                            <a href="{{ route('user.suggestion.detail', $suggestion->id) }}"
                                               style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; 
                                                      background: rgba(255, 255, 255, 0.2); 
                                                      border: 1px solid rgba(255, 255, 255, 0.3); padding: 0.4rem 0.8rem; 
                                                      border-radius: 2rem; color: white; font-size: 0.75rem; font-weight: 600; 
                                                      text-transform: uppercase; letter-spacing: 0.05em; 
                                                      transition: background 0.2s;"
                                               onmouseover="this.style.background='rgba(255, 255, 255, 0.3)'"
                                               onmouseout="this.style.background='rgba(255, 255, 255, 0.2)'">
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterCard = document.getElementById('filter-card');
            const collapseBtn = document.getElementById('collapse-btn');
            const filterContent = document.getElementById('filter-content');
            const filterForm = document.getElementById('projects-filters-form');
            
            // Force open by default as per request
            // const startCollapsed = {{ $startCollapsed ? 'true' : 'false' }};
            const startCollapsed = false; 

            // Location Wizard Elements
            const countrySelect = document.getElementById('country-filter');
            const citySelect = document.getElementById('city-filter');
            const districtSelect = document.getElementById('district-filter');
            const neighborhoodSelect = document.getElementById('neighborhood-filter');

            const cityWrapper = document.getElementById('city-wrapper');
            const districtWrapper = document.getElementById('district-wrapper');
            const neighborhoodWrapper = document.getElementById('neighborhood-wrapper');

            // Initial values from server
            const initialCountry = "{{ $filterValues['country'] ?? '' }}";
            const initialCity = "{{ $filterValues['city'] ?? '' }}";
            const initialDistrict = "{{ $filterValues['district'] ?? '' }}";
            const initialNeighborhood = "{{ $filterValues['neighborhood'] ?? '' }}";

            // Helper to fetch locations
            async function fetchLocations(parentId, targetSelect, type, selectedValue = null) {
                try {
                    const url = new URL("{{ route('api.locations') }}");
                    if (parentId) {
                        url.searchParams.append('parent_id', parentId);
                    }
                    if (type) {
                        url.searchParams.append('type', type);
                    }

                    const response = await fetch(url);
                    const locations = await response.json();

                    // Clear options
                    targetSelect.innerHTML = '<option value="">{{ __('common.select_option') }}</option>';

                    locations.forEach(location => {
                        const option = document.createElement('option');
                        option.value = location.name; // We submit the name
                        option.dataset.id = location.id; // We use ID for fetching children
                        option.textContent = location.name;
                        if (selectedValue && location.name === selectedValue) {
                            option.selected = true;
                        }
                        targetSelect.appendChild(option);
                    });

                    return locations;
                } catch (error) {
                    console.error('Error fetching locations:', error);
                    return [];
                }
            }

            // Initialize Wizard
            async function initWizard() {
                // Load Countries
                await fetchLocations(null, countrySelect, 'country', initialCountry);

                if (initialCountry) {
                    // Find country ID to load cities
                    const countryOption = Array.from(countrySelect.options).find(opt => opt.value === initialCountry);
                    if (countryOption) {
                        cityWrapper.style.display = 'block';
                        await fetchLocations(countryOption.dataset.id, citySelect, 'city', initialCity);
                    }
                }

                if (initialCity) {
                     const cityOption = Array.from(citySelect.options).find(opt => opt.value === initialCity);
                     if (cityOption) {
                         districtWrapper.style.display = 'block';
                         await fetchLocations(cityOption.dataset.id, districtSelect, 'district', initialDistrict);
                     }
                }

                if (initialDistrict) {
                    const districtOption = Array.from(districtSelect.options).find(opt => opt.value === initialDistrict);
                    if (districtOption) {
                        neighborhoodWrapper.style.display = 'block';
                        await fetchLocations(districtOption.dataset.id, neighborhoodSelect, 'neighborhood', initialNeighborhood);
                    }
                }
                
                syncContentHeight();
            }

            // Event Listeners for Wizard
            if (countrySelect) {
                countrySelect.addEventListener('change', async function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const countryId = selectedOption.dataset.id;

                    // Reset downstream
                    citySelect.innerHTML = '<option value="">{{ __('common.select_option') }}</option>';
                    districtSelect.innerHTML = '<option value="">{{ __('common.select_option') }}</option>';
                    neighborhoodSelect.innerHTML = '<option value="">{{ __('common.select_option') }}</option>';
                    
                    cityWrapper.style.display = 'none';
                    districtWrapper.style.display = 'none';
                    neighborhoodWrapper.style.display = 'none';

                    if (countryId) {
                        cityWrapper.style.display = 'block';
                        // Slide down effect
                        cityWrapper.style.opacity = '0';
                        cityWrapper.style.transform = 'translateY(-10px)';
                        cityWrapper.style.transition = 'opacity 0.3s, transform 0.3s';
                        
                        await fetchLocations(countryId, citySelect, 'city');
                        
                        requestAnimationFrame(() => {
                            cityWrapper.style.opacity = '1';
                            cityWrapper.style.transform = 'translateY(0)';
                        });
                    }
                    
                    syncContentHeight();
                });
            }

            if (citySelect) {
                citySelect.addEventListener('change', async function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const cityId = selectedOption.dataset.id;

                    districtSelect.innerHTML = '<option value="">{{ __('common.select_option') }}</option>';
                    neighborhoodSelect.innerHTML = '<option value="">{{ __('common.select_option') }}</option>';
                    
                    districtWrapper.style.display = 'none';
                    neighborhoodWrapper.style.display = 'none';

                    if (cityId) {
                        districtWrapper.style.display = 'block';
                        districtWrapper.style.opacity = '0';
                        districtWrapper.style.transform = 'translateY(-10px)';
                        districtWrapper.style.transition = 'opacity 0.3s, transform 0.3s';

                        await fetchLocations(cityId, districtSelect, 'district');

                        requestAnimationFrame(() => {
                            districtWrapper.style.opacity = '1';
                            districtWrapper.style.transform = 'translateY(0)';
                        });
                    }

                    syncContentHeight();
                });
            }

            if (districtSelect) {
                districtSelect.addEventListener('change', async function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const districtId = selectedOption.dataset.id;

                    neighborhoodSelect.innerHTML = '<option value="">{{ __('common.select_option') }}</option>';
                    neighborhoodWrapper.style.display = 'none';

                    if (districtId) {
                        neighborhoodWrapper.style.display = 'block';
                        neighborhoodWrapper.style.opacity = '0';
                        neighborhoodWrapper.style.transform = 'translateY(-10px)';
                        neighborhoodWrapper.style.transition = 'opacity 0.3s, transform 0.3s';

                        await fetchLocations(districtId, neighborhoodSelect, 'neighborhood');

                        requestAnimationFrame(() => {
                            neighborhoodWrapper.style.opacity = '1';
                            neighborhoodWrapper.style.transform = 'translateY(0)';
                        });
                    }
                    
                    syncContentHeight();
                });
            }

            // Collapse Logic
            if (filterCard && collapseBtn && filterContent) {
                const syncContentHeight = () => {
                    const isCollapsed = filterCard.classList.contains('collapsed');
                    if (isCollapsed) {
                        filterContent.style.maxHeight = '0px';
                    } else {
                        filterContent.style.maxHeight = `${filterContent.scrollHeight}px`;
                    }
                };

                const setCollapsedState = (collapsed, { immediate = false } = {}) => {
                    filterCard.classList.toggle('collapsed', collapsed);
                    if (immediate) {
                        filterContent.style.transition = 'none';
                    }
                    syncContentHeight();
                    if (immediate) {
                        requestAnimationFrame(() => {
                            filterContent.style.transition = '';
                        });
                    }
                };

                // Initialize state
                setCollapsedState(startCollapsed, { immediate: true });
                
                // Expose syncContentHeight to be used by wizard
                window.syncContentHeight = syncContentHeight;

                collapseBtn.addEventListener('click', () => {
                    const collapsed = !filterCard.classList.contains('collapsed');
                    setCollapsedState(collapsed);
                });

                window.addEventListener('resize', syncContentHeight);
            }

            // Initialize Wizard
            initWizard();

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
</script>

    <!-- Feedback Form Modal -->
    <div id="feedback-modal" style="display: none; position: fixed; inset: 0; z-index: 9999; align-items: center; justify-content: center;">
        <!-- Backdrop -->
        <div id="modal-backdrop" onclick="closeFeedbackModal()" style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(59, 130, 246, 0.15) 100%); backdrop-filter: blur(8px); opacity: 0; transition: opacity 0.3s ease;"></div>

        <!-- Modal Content -->
        <div id="modal-content" style="position: relative; background: linear-gradient(145deg, #ffffff 0%, #f0fdf4 50%, #ecfeff 100%); border-radius: 1.5rem; padding: 2rem; width: 100%; max-width: 420px; margin: 1rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25), 0 0 0 1px rgba(16, 185, 129, 0.1); transform: scale(0.95); opacity: 0; transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);">
            <!-- Close Button -->
            <button onclick="closeFeedbackModal()" style="position: absolute; top: 1rem; right: 1rem; width: 32px; height: 32px; border-radius: 50%; border: none; background: rgba(0,0,0,0.05); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                <svg style="width: 16px; height: 16px; color: #6b7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Animated Icon -->
            <div style="width: 5rem; height: 5rem; margin: 0 auto 1.5rem; background: linear-gradient(135deg, var(--green-100, #dcfce7) 0%, var(--green-200, #bbf7d0) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 24px rgba(34, 197, 94, 0.2);">
                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52" style="width: 3rem; height: 3rem; stroke: var(--green-600, #059669); stroke-width: 4; fill: none; stroke-linecap: round; stroke-linejoin: round; display: block; margin: 0 auto;">
                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                </svg>
            </div>

            <!-- Title -->
            <h3 id="modal-title" style="font-size: 1.5rem; font-weight: 700; color: var(--gray-900, #111827); text-align: center; margin-bottom: 0.5rem; font-family: inherit;">Teşekkür Ederiz!</h3>
            <p id="modal-message" style="font-size: 0.95rem; color: var(--gray-500, #6b7280); text-align: center; line-height: 1.5; margin-bottom: 1.5rem; font-family: inherit;">Oyunuz alındı! Bize biraz daha bilgi paylaşır mısınız?</p>

            <!-- Feedback Form -->
            <form id="feedback-form" style="display: flex; flex-direction: column; gap: 1rem;">
                <input type="hidden" id="feedback-like-id" name="like_id" value="">

                <!-- Age Field -->
                <div style="display: flex; flex-direction: column; gap: 0.375rem;">
                    <label for="feedback-age" style="font-size: 0.875rem; font-weight: 600; color: var(--gray-700, #374151);">Yaşınız</label>
                    <input type="number" id="feedback-age" name="age" min="1" max="120" placeholder="Örn: 25" required style="padding: 0.75rem 1rem; border: 2px solid var(--gray-200, #e5e7eb); border-radius: 0.75rem; font-size: 1rem; transition: all 0.2s; outline: none; background: white;" onfocus="this.style.borderColor='var(--green-500, #22c55e)'; this.style.boxShadow='0 0 0 3px rgba(34, 197, 94, 0.15)';" onblur="this.style.borderColor='var(--gray-200, #e5e7eb)'; this.style.boxShadow='none';">
                </div>

                <!-- Gender Field -->
                <div style="display: flex; flex-direction: column; gap: 0.375rem;">
                    <label for="feedback-gender" style="font-size: 0.875rem; font-weight: 600; color: var(--gray-700, #374151);">Cinsiyet</label>
                    <select id="feedback-gender" name="gender" required style="padding: 0.75rem 1rem; border: 2px solid var(--gray-200, #e5e7eb); border-radius: 0.75rem; font-size: 1rem; transition: all 0.2s; outline: none; background: white; cursor: pointer;" onfocus="this.style.borderColor='var(--green-500, #22c55e)'; this.style.boxShadow='0 0 0 3px rgba(34, 197, 94, 0.15)';" onblur="this.style.borderColor='var(--gray-200, #e5e7eb)'; this.style.boxShadow='none';">
                        <option value="" disabled selected>Seçiniz</option>
                        <option value="erkek">Erkek</option>
                        <option value="kadın">Kadın</option>
                        <option value="diğer">Diğer</option>
                    </select>
                </div>



                <!-- Submit Button -->
                <button type="submit" style="width: 100%; padding: 0.875rem; background: linear-gradient(135deg, var(--green-500, #22c55e) 0%, var(--green-600, #059669) 100%); color: white; border: none; border-radius: 0.75rem; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3); margin-top: 0.5rem;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(34, 197, 94, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(34, 197, 94, 0.3)';">
                    Gönder
                </button>

                <!-- Skip Button -->
                <button type="button" onclick="closeFeedbackModal()" style="width: 100%; padding: 0.75rem; background: transparent; color: var(--gray-500, #6b7280); border: none; font-weight: 500; font-size: 0.875rem; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.color='var(--gray-700, #374151)';" onmouseout="this.style.color='var(--gray-500, #6b7280)';">
                    Atla
                </button>
            </form>
        </div>
    </div>

    <!-- Simple Success Modal (for switch messages) -->
    <div id="success-modal" style="display: none; position: fixed; inset: 0; z-index: 9999; align-items: center; justify-content: center;">
        <!-- Backdrop -->
        <div id="success-modal-backdrop" style="position: absolute; inset: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); opacity: 0; transition: opacity 0.3s ease;"></div>

        <!-- Modal Content -->
        <div id="success-modal-content" style="position: relative; background: white; border-radius: 1.5rem; padding: 2.5rem; width: 100%; max-width: 400px; margin: 1rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); transform: scale(0.95); opacity: 0; transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);">
            <!-- Animated Icon -->
            <div style="width: 5rem; height: 5rem; margin: 0 auto 1.5rem; background: var(--green-50, #ecfdf5); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <svg class="checkmark-success" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52" style="width: 3rem; height: 3rem; stroke: var(--green-600, #059669); stroke-width: 4; fill: none; stroke-linecap: round; stroke-linejoin: round; display: block; margin: 0 auto;">
                    <circle class="checkmark__circle-success" cx="26" cy="26" r="25" fill="none"/>
                    <path class="checkmark__check-success" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                </svg>
            </div>

            <!-- Text -->
            <h3 id="success-modal-title" style="font-size: 1.5rem; font-weight: 700; color: var(--gray-900, #111827); text-align: center; margin-bottom: 0.5rem; font-family: inherit;">Teşekkür Ederiz!</h3>
            <p id="success-modal-message" style="font-size: 1rem; color: var(--gray-500, #6b7280); text-align: center; line-height: 1.5; margin-bottom: 2rem; font-family: inherit;">Geri bildiriminiz bizim için değerli.</p>

            <!-- Button -->
            <button onclick="closeSuccessModal()" style="width: 100%; padding: 0.875rem; background: var(--green-600, #059669); color: white; border: none; border-radius: 0.75rem; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);" onmouseover="this.style.background='var(--green-700, #047857)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.1)';" onmouseout="this.style.background='var(--green-600, #059669)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)';">
                Tamam
            </button>
        </div>
    </div>

    <style>
        /* SVG Animation Styles */
        .checkmark__circle,
        .checkmark__circle-success {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 2;
            stroke-miterlimit: 10;
            stroke: var(--green-600, #059669);
            fill: none;
            /* Animation will be triggered by JS adding a class */
        }

        .checkmark__check,
        .checkmark__check-success {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            /* Animation will be triggered by JS adding a class */
        }

        .animate-circle {
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }

        .animate-check {
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.6s forwards;
        }

        @keyframes stroke {
            100% {
                stroke-dashoffset: 0;
            }
        }
    </style>

    <script>
    let modalTimer;

    // Show feedback form modal
    function showFeedbackModal(likeId) {
        const modal = document.getElementById('feedback-modal');
        const backdrop = document.getElementById('modal-backdrop');
        const content = document.getElementById('modal-content');
        const circle = document.querySelector('.checkmark__circle');
        const check = document.querySelector('.checkmark__check');
        
        // Set like ID
        document.getElementById('feedback-like-id').value = likeId;
        
        // Reset form
        document.getElementById('feedback-form').reset();

        modal.style.display = 'flex';
        
        // Trigger entrance animation
        setTimeout(() => {
            backdrop.style.opacity = '1';
            content.style.opacity = '1';
            content.style.transform = 'scale(1)';
            
            // Trigger SVG animation
            circle.classList.remove('animate-circle');
            check.classList.remove('animate-check');
            void circle.offsetWidth; // trigger reflow
            circle.classList.add('animate-circle');
            check.classList.add('animate-check');
        }, 10);
    }

    function closeFeedbackModal() {
        const modal = document.getElementById('feedback-modal');
        const backdrop = document.getElementById('modal-backdrop');
        const content = document.getElementById('modal-content');

        if (!modal) return;

        // Kullanıcı iptal ederse anonim olarak kaydet
        const likeId = document.getElementById('feedback-like-id').value;
        if (likeId) {
            $.ajax({
                url: `/likes/${likeId}/update-feedback`,
                method: 'POST',
                data: {
                    age: null,
                    gender: null,
                    is_anonymous: 1
                },
                success: function(response) {
                    console.log('Anonim feedback kaydedildi');
                },
                error: function(xhr) {
                    console.error('Anonim feedback kaydedilemedi');
                }
            });
        }

        backdrop.style.opacity = '0';
        content.style.opacity = '0';
        content.style.transform = 'scale(0.95)';

        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    // Show simple success modal (for switch messages)
    function showSuccessModal(title, message) {
        const modal = document.getElementById('success-modal');
        const backdrop = document.getElementById('success-modal-backdrop');
        const content = document.getElementById('success-modal-content');
        const circle = document.querySelector('.checkmark__circle-success');
        const check = document.querySelector('.checkmark__check-success');
        
        if (title) document.getElementById('success-modal-title').textContent = title;
        if (message) document.getElementById('success-modal-message').textContent = message;

        modal.style.display = 'flex';
        
        // Trigger entrance animation
        setTimeout(() => {
            backdrop.style.opacity = '1';
            content.style.opacity = '1';
            content.style.transform = 'scale(1)';
            
            // Trigger SVG animation
            if (circle && check) {
                circle.classList.remove('animate-circle');
                check.classList.remove('animate-check');
                void circle.offsetWidth; // trigger reflow
                circle.classList.add('animate-circle');
                check.classList.add('animate-check');
            }
        }, 10);

        // Auto close
        if (modalTimer) clearTimeout(modalTimer);
        modalTimer = setTimeout(closeSuccessModal, 3000);
    }

    function closeSuccessModal() {
        const modal = document.getElementById('success-modal');
        const backdrop = document.getElementById('success-modal-backdrop');
        const content = document.getElementById('success-modal-content');

        if (!modal) return;

        backdrop.style.opacity = '0';
        content.style.opacity = '0';
        content.style.transform = 'scale(0.95)';

        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    // Handle feedback form submission
    document.addEventListener('DOMContentLoaded', function() {
        const feedbackForm = document.getElementById('feedback-form');
        if (feedbackForm) {
            feedbackForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const likeId = document.getElementById('feedback-like-id').value;
                const age = document.getElementById('feedback-age').value;
                const gender = document.getElementById('feedback-gender').value;

                $.ajax({
                    url: `/likes/${likeId}/update-feedback`,
                    method: 'POST',
                    data: {
                        age: age,
                        gender: gender,
                        is_anonymous: 0  // Gönder butonu = anonim değil
                    },
                    success: function(response) {
                        // Like ID'yi temizle - closeFeedbackModal tekrar anonim kayıt göndermesin
                        document.getElementById('feedback-like-id').value = '';
                        closeFeedbackModal();
                        showSuccessModal('Teşekkürler!', response.message || 'Geri bildiriminiz kaydedildi.');
                    },
                    error: function(xhr) {
                        let message = 'Bir hata oluştu.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        showMessage(message, 'error');
                    }
                });
            });
        }
    });

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

    // Group buttons by project (fallback to category if project id is missing)
    const suggestionProjectId = clickedButton.getAttribute('data-project-id');
    const suggestionCategory = clickedButton.getAttribute('data-category') || 'default';
    const groupSelector = suggestionProjectId
        ? `[data-project-id="${suggestionProjectId}"]`
        : `[data-category="${suggestionCategory}"]`;
    const groupedButtons = document.querySelectorAll(groupSelector);

    // Disable all buttons in this project/category during request
    groupedButtons.forEach(btn => {
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
            // Reset all buttons in the same project/category to default state (radio button logic)
            groupedButtons.forEach(btn => {
                // Skip expired buttons
                if (btn.getAttribute('data-expired') === 'true') {
                    // Still update like counters for expired buttons
                    const expiredSuggestionId = btn.getAttribute('data-suggestion-id');
                    if (response.all_likes && response.all_likes[expiredSuggestionId] !== undefined) {
                        const expiredLikeCount = btn.querySelector('.like-count');
                        if (expiredLikeCount) {
                            expiredLikeCount.textContent = response.all_likes[expiredSuggestionId];
                        }
                    }
                    return;
                }

                btn.classList.remove('liked');
                const btnSuggestionId = btn.getAttribute('data-suggestion-id');

                // Reset heart icon to outline for all buttons in group
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
                    showSuccessModal(
                        'Teşekkürler!',
                        `Seçiminiz "${response.switched_from}" önerisinden "${response.current_title}" önerisine değiştirildi.`
                    );
                } else if (response.need_feedback && response.like_id) {
                    // Show feedback form for new likes
                    showFeedbackModal(response.like_id);
                } else {
                    showSuccessModal(
                        'Teşekkürler!',
                        'Geri bildiriminiz için teşekkür ederiz.'
                    );
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
            // Re-enable all non-expired buttons in the group
            groupedButtons.forEach(btn => {
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
@livewire('take-survey')
@endsection

