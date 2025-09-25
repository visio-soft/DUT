@extends('user.layout')

@section('title', $project->name . ' Önerileri - DUT Vote')

@section('content')
<!-- CSS Styles -->
<style>
    /* Root Variables */
    :root {
        --green-50: #f0fdf4;
        --green-100: #dcfce7;
        --green-200: #bbf7d0;
        --green-300: #86efac;
        --green-400: #4ade80;
        --green-500: #22c55e;
        --green-600: #16a34a;
        --green-700: #15803d;
        --green-800: #166534;
        --blue-50: #eff6ff;
        --blue-100: #dbeafe;
        --blue-400: #60a5fa;
        --blue-500: #3b82f6;
        --blue-600: #2563eb;
        --blue-700: #1d4ed8;
        --red-400: #f87171;
        --red-500: #ef4444;
        --red-600: #dc2626;
        --yellow-100: #fef3c7;
        --yellow-600: #d97706;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-400: #9ca3af;
        --gray-500: #6b7280;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-800: #1f2937;
        --gray-900: #111827;
        --radius-sm: 0.375rem;
        --radius-md: 0.5rem;
        --radius-lg: 0.75rem;
        --radius-xl: 1rem;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    }

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

    .back-button {
        background: var(--green-100);
        border: 1px solid var(--green-300);
        color: var(--green-700);
        padding: 0.75rem;
        border-radius: 50%;
        text-decoration: none;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .back-button:hover {
        background: var(--green-200);
        border-color: var(--green-400);
        color: var(--green-800);
        transform: translateX(-2px);
    }

    .project-icon {
        width: 3rem;
        height: 3rem;
        color: var(--green-600);
    }

    .project-title-section {
        text-align: left;
    }

    .project-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0;
        line-height: 1.1;
    }

    .project-subtitle {
        font-size: 1.125rem;
        color: var(--gray-600);
        margin: 0.5rem 0 0;
    }

    /* Statistics Cards */
    .stats-container {
        display: flex;
        justify-content: center;
        gap: 2rem;
        max-width: 800px;
        margin: 0 auto;
    }

    .stat-card {
        background: white;
        border-radius: var(--radius-xl);
        padding: 2rem;
        text-align: center;
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow-md);
        flex: 1;
        min-width: 200px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .stat-icon-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .stat-icon {
        border-radius: 50%;
        padding: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon.blue {
        background: var(--blue-100);
    }

    .stat-icon.red {
        background: #fef2f2;
    }

    .stat-icon.yellow {
        background: var(--yellow-100);
    }

    .stat-icon svg {
        width: 2rem;
        height: 2rem;
    }

    .stat-icon.blue svg {
        color: var(--blue-600);
    }

    .stat-icon.red svg {
        color: var(--red-600);
    }

    .stat-icon.yellow svg {
        color: var(--yellow-600);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .stat-number.blue {
        color: var(--blue-700);
    }

    .stat-number.red {
        color: var(--red-600);
    }

    .stat-number.yellow {
        color: var(--yellow-600);
    }

    .stat-label {
        font-size: 1rem;
        color: var(--gray-600);
        margin: 0;
        font-weight: 500;
    }

    /* Main Content Layout */
    .main-content {
        padding: 2rem 0;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 1fr 3fr;
        gap: 2rem;
    }

    /* Sidebar Styles */
    .sidebar {
        position: sticky;
        top: 2rem;
        height: calc(100vh - 4rem);
        overflow-y: auto;
        overflow-x: hidden;
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        /* Smooth scrolling for sidebar */
        scroll-behavior: smooth;
        /* Webkit scrollbar styling */
        scrollbar-width: thin;
        scrollbar-color: var(--gray-300) transparent;
    }

    /* Custom scrollbar for webkit browsers */
    .sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 3px;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: var(--gray-300);
        border-radius: 3px;
        transition: background 0.2s;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background: var(--gray-400);
    }

    .sidebar-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        gap: 0.5rem;
    }

    .sidebar-icon {
        width: 1.25rem;
        height: 1.25rem;
        color: var(--blue-600);
    }

    .sidebar-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--gray-900);
        margin: 0;
    }

    .voting-info {
        background: var(--green-50);
        border: 1px solid var(--green-200);
        border-radius: var(--radius-md);
        padding: 0.75rem;
        margin-bottom: 1rem;
    }

    .voting-info-content {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .voting-info-icon {
        width: 1rem;
        height: 1rem;
        color: var(--green-600);
        margin-top: 0.125rem;
        flex-shrink: 0;
    }

    .voting-info-text {
        font-size: 0.75rem;
        color: var(--green-700);
        margin: 0;
        line-height: 1.4;
        font-weight: 500;
    }

    /* Table Header */
    .table-header {
        display: grid;
        grid-template-columns: 2fr 80px 80px;
        gap: 0.5rem;
        padding: 0.75rem;
        background: var(--gray-100);
        border-radius: var(--radius-md);
        margin-bottom: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--gray-700);
    }

    .table-header-cell {
        text-align: left;
    }

    .table-header-cell.center {
        text-align: center;
    }

    /* Suggestions List */
    .suggestions-list {
        overflow-y: visible;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-md);
    }

    .suggestion-item {
        display: grid;
        grid-template-columns: 2fr 80px 80px;
        gap: 0.5rem;
        padding: 0.75rem;
        border-bottom: 1px solid var(--gray-200);
        background: white;
        transition: all 0.2s;
        cursor: pointer;
    }

    .suggestion-item:hover {
        background: var(--blue-50);
        border-left: 3px solid var(--blue-500);
        padding-left: calc(0.75rem - 3px);
    }

    .suggestion-item.active {
        background: var(--blue-50);
        border-left: 3px solid var(--blue-600);
        padding-left: calc(0.75rem - 3px);
    }

    .suggestion-item:last-child {
        border-bottom: none;
    }

    .suggestion-name {
        min-width: 0;
    }

    .suggestion-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--gray-900);
        margin-bottom: 0.25rem;
        line-height: 1.2;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .suggestion-author {
        font-size: 0.75rem;
        color: var(--gray-500);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .suggestion-stat {
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
    }

    .suggestion-stat.liked {
        color: var(--red-500);
        font-weight: 600;
    }

    .suggestion-stat.normal {
        color: var(--gray-600);
        font-weight: 500;
    }

    .suggestion-stat.comments {
        color: var(--blue-600);
        font-weight: 500;
    }

    /* Suggestion Cards */
    .suggestions-container {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .suggestion-card {
        overflow: hidden;
        position: relative;
        min-height: 400px;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        background: var(--gray-100);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .suggestion-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
    }

    /* Card Background */
    .card-background {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1;
    }

    .card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        filter: brightness(0.4) saturate(1.2);
        transition: filter 0.3s ease;
    }

    .card-gradient {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 50%, #EC4899 100%);
        position: relative;
    }

    .card-pattern {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.3;
    }

    .card-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.1) 40%, rgba(0,0,0,0.4) 100%);
        z-index: 2;
    }

    /* Card Content */
    .card-content {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 3;
        padding: 2rem;
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .card-top-section {
        flex: 1;
    }

    .card-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        gap: 0.75rem;
    }

    .card-icon {
        width: 1.5rem;
        height: 1.5rem;
        color: rgba(255,255,255,0.9);
    }

    .card-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: white;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.7);
        line-height: 1.2;
    }

    .card-author-section {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        gap: 0.5rem;
    }

    .card-author-icon {
        width: 1rem;
        height: 1rem;
        color: rgba(255,255,255,0.8);
    }

    .card-author {
        font-size: 0.875rem;
        color: rgba(255,255,255,0.9);
        text-shadow: 0 1px 2px rgba(0,0,0,0.5);
    }

    .card-description-box {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border-radius: 0.75rem;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .card-description {
        font-size: 1rem;
        color: rgba(255,255,255,0.95);
        margin: 0;
        text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        line-height: 1.6;
    }

    /* Card Bottom Section */
    .card-bottom-section {
        background: rgba(0,0,0,0.3);
        backdrop-filter: blur(15px);
        border-radius: 0.75rem;
        padding: 1.25rem;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .card-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 1rem;
    }

    .card-stat {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: rgba(255,255,255,0.9);
        font-size: 0.875rem;
        font-weight: 500;
    }

    .card-stat-icon {
        width: 1rem;
        height: 1rem;
    }

    .card-stat.likes .card-stat-icon {
        color: #ef4444;
        fill: currentColor;
    }

    .card-stat.comments .card-stat-icon {
        color: #3b82f6;
    }

    .card-stat.date {
        color: rgba(255,255,255,0.7);
        font-size: 0.75rem;
    }

    .card-stat.date .card-stat-icon {
        width: 0.875rem;
        height: 0.875rem;
    }

    /* Card Actions */
    .card-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    .like-button {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 0.75rem 1.25rem;
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        cursor: pointer;
        box-shadow: var(--shadow-md);
        text-decoration: none;
    }

    .like-button:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .like-button.liked {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border-color: #dc2626;
    }

    .like-button.liked:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        border-color: #b91c1c;
    }

    .like-button-icon {
        width: 1rem;
        height: 1rem;
    }

    .like-button.liked .like-button-icon {
        fill: currentColor;
    }

    .detail-button {
        background: rgba(255,255,255,0.95);
        color: #1e40af;
        padding: 0.75rem 1.25rem;
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.5);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: var(--shadow-md);
    }

    .detail-button:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        background: white;
        color: #1e40af;
    }

    .detail-button-icon {
        width: 1rem;
        height: 1rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem;
        max-width: 400px;
        margin: 0 auto;
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
    }

    .empty-state-icon {
        width: 4rem;
        height: 4rem;
        margin: 0 auto 1rem;
        color: var(--blue-400);
    }

    .empty-state-title-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        gap: 0.5rem;
    }

    .empty-state-title-icon {
        width: 1.25rem;
        height: 1.25rem;
        color: var(--blue-600);
    }

    .empty-state-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--gray-900);
        margin: 0;
    }

    .empty-state-description {
        color: var(--gray-500);
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .empty-state-description-icon {
        width: 1rem;
        height: 1rem;
        color: var(--blue-500);
    }

    /* Debug Styles */
    .debug-info {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 0.5rem;
        border-radius: 4px;
        font-size: 10px;
        z-index: 10;
        max-width: 200px;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .sidebar {
            position: static;
            height: auto;
            max-height: 60vh;
            overflow-y: auto;
        }
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 2rem 0 !important;
        }

        .project-title {
            font-size: 2rem;
        }

        .header-title-wrapper {
            flex-direction: column;
            gap: 1rem;
        }

        .project-title-section {
            text-align: center;
        }

        .stats-container {
            flex-direction: column;
            gap: 1rem;
            max-width: 400px;
        }

        .content-grid {
            grid-template-columns: 280px 1fr;
            gap: 1rem;
        }

        .sidebar {
            position: static;
            max-height: 50vh;
        }

        .sidebar-title {
            font-size: 1rem;
        }

        .suggestions-list {
            max-height: 50vh;
        }

        .table-header {
            font-size: 0.7rem;
            padding: 0.5rem;
        }

        .suggestion-item {
            padding: 0.5rem;
        }

        .suggestion-title {
            font-size: 0.8rem;
        }

        .suggestion-author {
            font-size: 0.7rem;
        }

        .card-content {
            padding: 1.5rem;
        }

        .card-title {
            font-size: 1.5rem;
        }

        .card-actions {
            flex-direction: column;
            gap: 0.75rem;
        }

        .like-button,
        .detail-button {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 640px) {
        .user-container {
            padding: 0 1rem !important;
        }

        .content-grid {
            grid-template-columns: 1fr !important;
            gap: 1rem !important;
        }

        .sidebar {
            position: static;
            order: 2;
        }

        .suggestions-container {
            order: 1;
        }

        .suggestion-item {
            grid-template-columns: 1fr 60px 60px;
            font-size: 0.8rem;
        }

        .table-header {
            grid-template-columns: 1fr 60px 60px;
        }
    }

    /* Animation Keyframes */
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

    @media (min-width: 641px) {
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
</style>

<!-- Header Section -->
<section class="page-header">
    <div class="user-container">
        <!-- Page Header -->
        <div class="header-content">
            <div class="header-title-wrapper">
                <a href="{{ route('user.projects') }}" class="back-button">
                    <svg class="back-button-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <svg class="project-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                </svg>
                <div class="project-title-section">
                    <h1 class="project-title">{{ $project->name }}</h1>
                    <p class="project-subtitle">Proje Önerileri</p>
                </div>
            </div>

            @php
                $totalSuggestions = $project->oneriler->count();
                $totalLikes = $project->oneriler->sum(function($suggestion) {
                    return $suggestion->likes->count();
                });
                $totalComments = $project->oneriler->sum(function($suggestion) {
                    return $suggestion->comments->count();
                });
            @endphp

            @if($totalSuggestions > 0)
            <!-- Statistics Cards -->
            <div class="stats-container">
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
                    <p class="stat-label">Toplam Öneri</p>
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
                    <p class="stat-label">Toplam Beğeni</p>
                </div>

                <!-- Total Comments Card -->
                <div class="stat-card">
                    <div class="stat-icon-wrapper">
                        <div class="stat-icon yellow">
                            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.20-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="stat-number yellow">{{ $totalComments }}</h3>
                    <p class="stat-label">Toplam Yorum</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Main Content Section -->
<div class="section-padding">
    <div class="user-container">
        @if($project->oneriler->count() > 0)
        <div class="d-grid" style="grid-template-columns: 1fr 3fr; gap: 2rem;">
            <!-- Sol Taraf: Suggestion Tree View -->
            <div>
                <div class="tree-view">
                    <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                        <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem; color: var(--green-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
                        </svg>
                        <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-900); margin: 0;">Öneri Listesi</h3>
                    </div>

                    <!-- Info about voting system -->
                    <div style="background: var(--green-50); border: 1px solid var(--green-200); border-radius: var(--radius-md); padding: 0.75rem; margin-bottom: 1rem;">
                        <div style="display: flex; align-items: start; gap: 0.5rem;">
                            <svg style="width: 1rem; height: 1rem; color: var(--green-600); margin-top: 0.125rem; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                            </svg>
                            <div>
                                <p style="font-size: 0.75rem; color: var(--green-700); margin: 0; line-height: 1.4; font-weight: 500;">
                                    <strong>Oylama Sistemi:</strong> Bu proje kategorisinde sadece bir öneri için oy kullanabilirsiniz.
                                </p>
                                <p style="font-size: 0.7rem; color: var(--green-600); margin: 0.25rem 0 0; line-height: 1.3;">
                                    Seçiminizi değiştirmek için başka bir öneriye tıklayın. ○ işareti mevcut seçiminizi gösterir.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div style="space-y: 0.5rem;">
                        <!-- Project Node -->
                        <div style="border-bottom: 1px solid var(--green-100); padding-bottom: 0.5rem;">
                            <div class="tree-project" style="display: flex; align-items: center; padding: 0.5rem; cursor: pointer; border-radius: var(--radius-md); transition: all 0.2s;">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem; color: var(--green-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                                </svg>
                                <span style="font-size: 0.875rem; font-weight: 500; color: var(--gray-900);">{{ $project->name }}</span>
                                <span style="margin-left: auto; font-size: 0.75rem; color: var(--gray-500);">({{ $project->oneriler->count() }})</span>
                            </div>

                            <!-- Suggestions -->
                            <div class="tree-suggestions" style="margin-left: 1rem; margin-top: 0.5rem;">
                                @foreach($project->oneriler->sortByDesc(function($suggestion) { return $suggestion->likes->count(); }) as $suggestion)
                                <div class="tree-suggestion" style="display: flex; align-items: center; padding: 0.375rem 0.5rem; margin-bottom: 0.25rem; cursor: pointer; border-radius: var(--radius-sm); transition: all 0.2s; font-size: 0.8rem;"
                                     onclick="scrollToSuggestion({{ $suggestion->id }})">
                                    <svg style="width: 0.75rem; height: 0.75rem; margin-right: 0.5rem; color: var(--green-500);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
                                    </svg>
                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ Str::limit($suggestion->title, 20) }}</span>
                                    <span style="margin-left: auto; display: flex; align-items: center;">
                                        <svg style="width: 0.75rem; height: 0.75rem; color: var(--red-500); margin-right: 0.25rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                        </svg>
                                        {{ $suggestion->likes->count() }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sağ Taraf: Suggestion Cards -->
            <div>
                <div class="d-flex" style="flex-direction: column; gap: 2rem;">
                    @foreach($project->oneriler->sortByDesc(function($suggestion) { return $suggestion->likes->count(); }) as $suggestion)
                    <div id="suggestion-{{ $suggestion->id }}" class="user-card" style="overflow: hidden; position: relative; min-height: 200px;">
                        <!-- Suggestion Background Image -->
                        @php
                            $suggestionImage = null;
                            // 1. Önce media library'den dene
                            $mediaUrl = $suggestion->getFirstMediaUrl('images');

                        @endphp

                        <!-- Suggestion Background Image -->
                        @if($suggestion->getFirstMediaUrl('images'))
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 1;">
                            <img src="{{ $suggestion->getFirstMediaUrl('images') }}"
                                 alt="{{ $suggestion->title }}"
                                 style="width: 100%; height: 100%; object-fit: cover; filter: brightness(0.3);"
                                 onerror="this.style.display='none';">
                        </div>
                        @else
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 1; background: linear-gradient(135deg, var(--green-600) 0%, var(--green-700) 100%); opacity: 0.8;"></div>
                        @endif

                        <!-- Suggestion Overlay -->
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); z-index: 2;"></div>

                        <!-- Suggestion Content -->
                        <div style="position: relative; z-index: 3; padding: 2rem; color: white;">
                            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                                <svg style="width: 1.5rem; height: 1.5rem; margin-right: 0.75rem; color: rgba(255,255,255,0.9);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
                                </svg>
                                <h2 style="font-size: 1.75rem; font-weight: 700; color: white; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">{{ $suggestion->title }}</h2>
                            </div>

                            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem; color: rgba(255,255,255,0.8);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                                <span style="font-size: 0.875rem; color: rgba(255,255,255,0.9); text-shadow: 0 1px 2px rgba(0,0,0,0.5);">
                                    Öneren: {{ $suggestion->createdBy->name ?? 'Anonim' }}
                                </span>
                            </div>

                            @if($suggestion->description)
                            <p style="font-size: 1rem; color: rgba(255,255,255,0.9); margin-bottom: 1.5rem; text-shadow: 0 1px 2px rgba(0,0,0,0.5); line-height: 1.5;">
                                <svg style="width: 1rem; height: 1rem; display: inline; margin-right: 0.5rem; color: rgba(255,255,255,0.8);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
                                </svg>
                                {{ $suggestion->description }}
                            </p>
                            @endif

                            <!-- Suggestion Meta -->
                            <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; color: rgba(255,255,255,0.9); font-size: 0.875rem;">
                                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                    </svg>
                                    {{ $suggestion->likes->count() }} Beğeni
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; color: rgba(255,255,255,0.9); font-size: 0.875rem;">
                                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.627 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
                                    </svg>
                                    {{ $suggestion->comments->count() }} Yorum
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; color: rgba(255,255,255,0.9); font-size: 0.875rem;">
                                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                    </svg>
                                    {{ $suggestion->created_at->format('d.m.Y') }}
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div style="display: flex; gap: 1rem; align-items: center;">
                                <!-- Like Button -->
                                <button onclick="toggleLike({{ $suggestion->id }})"
                                        class="btn-like btn-like-large {{ Auth::check() && $suggestion->likes->where('user_id', Auth::id())->count() > 0 ? 'liked' : '' }}"
                                        data-suggestion-id="{{ $suggestion->id }}"
                                        data-project-id="{{ $project->id }}"
                                        data-category="{{ $suggestion->category_id ?? 'default' }}"
                                        title="Bu kategoride sadece bir öneri beğenilebilir (Radio buton mantığı)">
                                    <svg class="like-icon like-icon-large" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                    </svg>
                                    <span class="like-count">{{ $suggestion->likes->count() }}</span>
                                </button>

                                <!-- Detail Button -->
                                <a href="{{ route('user.suggestion.detail', $suggestion->id) }}" style="background: rgba(255,255,255,0.95); color: #1e40af; padding: 0.75rem 1.25rem; border-radius: var(--radius-md); font-size: 0.875rem; font-weight: 600; text-decoration: none; transition: all 0.3s ease; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.5); display: flex; align-items: center; gap: 0.5rem; box-shadow: var(--shadow-md);">
                                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                    </svg>
                                    Detay
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center section-padding-lg">
            <div class="user-card" style="max-width: 400px; margin: 0 auto; padding: 3rem;">
                <svg style="width: 4rem; height: 4rem; margin: 0 auto 1rem; color: var(--green-400);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
                </svg>
                <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                    <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem; color: var(--green-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z"/>
                    </svg>
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-900); margin: 0;">Bu proje için henüz öneri bulunmuyor</h3>
                </div>
                <p style="color: var(--gray-500);">
                    <svg style="width: 1rem; height: 1rem; display: inline; margin-right: 0.5rem; color: var(--green-500);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                    </svg>
                    İlk önerilerin eklenmesi bekleniyor.
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
// Set up CSRF token for AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Scroll to suggestion with enhanced animation
function scrollToSuggestion(suggestionId) {
    const element = document.getElementById('suggestion-' + suggestionId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });

        // Update sidebar active state
        updateSidebarActiveState(suggestionId);

        // Add highlight animation
        element.classList.add('suggestion-card');
        element.style.transform = 'translateY(-8px)';
        element.style.boxShadow = '0 20px 25px -5px rgb(59 130 246 / 0.4), 0 8px 10px -6px rgb(59 130 246 / 0.3)';

        setTimeout(() => {
            element.style.transform = '';
            element.style.boxShadow = '';
        }, 2000);
    }
}

// Update sidebar active state
function updateSidebarActiveState(suggestionId) {
    // Remove active class from all suggestion items
    const allSidebarItems = document.querySelectorAll('.suggestion-item');
    allSidebarItems.forEach(item => item.classList.remove('active'));

    // Add active class to current item
    const currentItem = document.querySelector(`[onclick="scrollToSuggestion(${suggestionId})"]`);
    if (currentItem) {
        currentItem.classList.add('active');
    }
}



// Toggle like with AJAX (Radio button logic: one per project category)
function toggleLike(suggestionId) {
    @guest
        showMessage('Beğeni yapmak için giriş yapmanız gerekiyor.', 'error');
        setTimeout(() => {
            window.location.href = '{{ route('user.login') }}';
        }, 2000);
        return;
    @endguest

    const clickedButton = document.querySelector(`[data-suggestion-id="${suggestionId}"]`);
    const likeCount = clickedButton.querySelector('.like-count');
    const projectId = {{ $project->id }};

    // Get current suggestion's category from the data attributes
    const suggestionCategory = clickedButton.getAttribute('data-category') || 'default';

    // Find all buttons in the same category (radio button behavior)
    const allButtonsInCategory = document.querySelectorAll(`[data-category="${suggestionCategory}"]`);

    // Disable all buttons in this category during request
    allButtonsInCategory.forEach(btn => {
        btn.disabled = true;
        btn.style.opacity = '0.7';
        btn.style.pointerEvents = 'none';
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
                btn.classList.remove('liked');
                const btnSuggestionId = btn.getAttribute('data-suggestion-id');

                // Reset heart icon to outline for all buttons in category
                const heartIcon = btn.querySelector('.like-button-icon') || btn.querySelector('.like-icon');
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
                const heartIcon = clickedButton.querySelector('.like-button-icon') || clickedButton.querySelector('.like-icon');
                if (heartIcon) {
                    heartIcon.style.fill = 'currentColor';
                }

                if (response.switched_from) {
                    showMessage(`✓ Seçiminiz "${response.switched_from}" önerisinden "${response.current_title}" önerisine değiştirildi.`, 'success');
                } else {
                    showMessage('✓ Öneri beğenildi! Bu kategoride sadece bir öneri beğenilebilir.', 'success');
                }
            } else {
                clickedButton.classList.remove('liked');

                // Reset heart icon to outline
                const heartIcon = clickedButton.querySelector('.like-button-icon') || clickedButton.querySelector('.like-icon');
                if (heartIcon) {
                    heartIcon.style.fill = 'none';
                }

                showMessage('Beğeni kaldırıldı.', 'info');
            }

            // Update sidebar like counts
            updateSidebarStats();
        },
        error: function(xhr) {
            let message = 'Bir hata oluştu.';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                message = xhr.responseJSON.error;
            }
            showMessage(message, 'error');
        },
        complete: function() {
            // Re-enable all buttons in the category
            allButtonsInCategory.forEach(btn => {
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.style.pointerEvents = 'auto';
            });
        }
    });
}

// Update sidebar statistics and indicators
function updateSidebarStats() {
    // Update like counts in sidebar - more careful approach
    const allCards = document.querySelectorAll('[id^="suggestion-"]');
    allCards.forEach(card => {
        const suggestionId = card.id.replace('suggestion-', '');
        const mainLikeCount = card.querySelector('.like-count');
        const sidebarItem = document.querySelector(`[onclick="scrollToSuggestion(${suggestionId})"]`);

        if (mainLikeCount && sidebarItem) {
            // Find the span that contains the like count (last span with heart icon)
            const sidebarSpan = sidebarItem.querySelector('span:last-child');
            if (sidebarSpan) {
                // Get the current HTML content and update just the number part
                const currentHTML = sidebarSpan.innerHTML;
                const heartIconHTML = sidebarSpan.querySelector('svg') ? sidebarSpan.querySelector('svg').outerHTML : '';

                if (heartIconHTML) {
                    // Reconstruct with heart icon + updated number
                    sidebarSpan.innerHTML = heartIconHTML + mainLikeCount.textContent;
                }
            }
        }
    });
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

// Add CSS for message animations
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

        /* Mobile responsiveness */
        @media (max-width: 1024px) {
            .d-grid[style*="grid-template-columns: 1fr 3fr"] {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }
        }

        @media (max-width: 768px) {
            .section-padding {
                padding: 2rem 0 !important;
            }

            .user-container {
                padding: 0 1rem !important;
            }
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

// Initialize page functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize suggestion list interactions
    const suggestionItems = document.querySelectorAll('.suggestion-item');
    suggestionItems.forEach(item => {
        item.addEventListener('click', function() {
            const suggestionId = this.getAttribute('data-id');
            if (suggestionId) {
                scrollToSuggestion(suggestionId);
            }
        });
    });

    // Initialize card hover effects
    const suggestionCards = document.querySelectorAll('.suggestion-card');
    suggestionCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });

    // Initialize stat card hover effects
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });

    // Smooth scrolling for internal links
    const backButton = document.querySelector('.back-button');
    if (backButton) {
        backButton.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(-2px)';
        });

        backButton.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    }

// Responsive grid adjustment for mobile (projects.blade.php format)
function adjustLayout() {
    const container = document.querySelector('[style*="grid-template-columns: 1fr 3fr"]');
    if (container && window.innerWidth < 1024) {
        container.style.gridTemplateColumns = '1fr';
        container.style.gap = '1rem';
    } else if (container) {
        container.style.gridTemplateColumns = '1fr 3fr';
        container.style.gap = '2rem';
    }

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
}// Call on load and resize
window.addEventListener('load', adjustLayout);
window.addEventListener('resize', adjustLayout);

    // Initialize tooltips for like buttons
    const likeButtons = document.querySelectorAll('.like-button');
    likeButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            // Add subtle pulse effect for better user feedback
            this.style.animation = 'none';
            setTimeout(() => {
                this.style.animation = 'pulse 1s infinite';
            }, 10);
        });

        button.addEventListener('mouseleave', function() {
            this.style.animation = 'none';
        });
    });

    // Add CSS for pulse animation
    if (!document.getElementById('pulse-animation')) {
        const style = document.createElement('style');
        style.id = 'pulse-animation';
        style.textContent = `
            @keyframes pulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.05); }
            }
        `;
        document.head.appendChild(style);
    }
});
</script>

@endsection
