@extends('user.layout')

@section('title', $project->name . ' ' . __('common.suggestions_title_suffix') . ' - DUT Vote')

@section('content')
<!-- CSS Styles -->
@include('user._shared-colors')
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

    .stats-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        background: linear-gradient(135deg, var(--blue-500), var(--blue-600));
        color: white;
        text-decoration: none;
        border-radius: var(--radius-lg);
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .stats-back-btn:hover {
        background: linear-gradient(135deg, var(--blue-600), var(--blue-700));
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.25);
        color: white;
    }

    .stats-back-btn svg {
        width: 1.125rem;
        height: 1.125rem;
        transition: transform 0.2s ease;
    }

    .stats-back-btn:hover svg {
        transform: translateX(-2px);
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

    .stat-icon.blue {
        background: linear-gradient(135deg, var(--blue-100), var(--blue-200));
        border: 2px solid var(--blue-300);
    }

    .stat-icon.red {
        background: linear-gradient(135deg, #fef2f2, #fce7e7);
        border: 2px solid var(--red-300);
    }

    .stat-icon.yellow {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border: 2px solid #fcd34d;
    }

    .stat-icon svg {
        width: 2.25rem;
        height: 2.25rem;
        transition: transform 0.3s ease;
    }

    .stat-card:hover .stat-icon svg {
        transform: scale(1.1);
    }

    .stat-icon.blue svg {
        color: var(--blue-600);
    }

    .stat-icon.red svg {
        color: var(--red-600);
    }

    .stat-icon.yellow svg {
        color: #d97706;
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

    .stat-number.blue {
        background: linear-gradient(135deg, var(--blue-700), var(--blue-500));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-number.red {
        background: linear-gradient(135deg, var(--red-600), var(--red-400));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-number.yellow {
        background: linear-gradient(135deg, #d97706, #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        /* Fallback for browsers that don't support background-clip */
        color: #d97706;
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

    /* Main Content Layout */
    .main-content {
        padding: 2rem 0;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 1.5rem;
        align-items: start;
    }

    @media (min-width: 1024px) {
        .content-grid {
            grid-template-columns: 320px 1fr;
            gap: 2rem;
        }
    }

    @media (min-width: 1280px) {
        .content-grid {
            grid-template-columns: 340px 1fr;
            gap: 2.5rem;
        }
    }

    /* Sidebar Styles */
    .sidebar {
        position: sticky;
        top: 5.5rem;
        height: calc(100vh - 7rem);
        overflow-y: auto;
        overflow-x: hidden;
        background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
        border: 1px solid var(--green-200);
        border-radius: var(--radius-lg);
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(26, 191, 107, 0.06);
        transition: all 0.3s ease;
        /* Smooth scrolling for sidebar */
        scroll-behavior: smooth;
        /* Webkit scrollbar styling */
        scrollbar-width: thin;
        scrollbar-color: var(--green-300) transparent;
        width: 100%;
    }

    @media (min-width: 1024px) {
        .sidebar {
            padding: 1.25rem;
            border-radius: var(--radius-xl);
        }
    }

    @media (min-width: 1280px) {
        .sidebar {
            padding: 1.5rem;
        }
    }

    .sidebar:hover {
        box-shadow: 0 4px 12px rgba(26, 191, 107, 0.1);
        border-color: var(--green-300);
    }

    /* Custom scrollbar for webkit browsers */
    .sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: var(--green-50);
        border-radius: 3px;
        margin: 2px 0;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, var(--green-300) 0%, var(--green-400) 100%);
        border-radius: 3px;
        transition: all 0.3s ease;
        border: 1px solid var(--green-50);
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, var(--green-400) 0%, var(--green-500) 100%);
        border-color: var(--green-100);
    }

    .sidebar-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        gap: 0.5rem;
        padding-bottom: 0.875rem;
        border-bottom: 2px solid var(--green-100);
    }

    .sidebar-icon {
        width: 1.25rem;
        height: 1.25rem;
        color: var(--green-600);
        flex-shrink: 0;
    }

    @media (min-width: 1280px) {
        .sidebar-icon {
            width: 1.375rem;
            height: 1.375rem;
        }
    }

    .sidebar-title {
        font-size: 0.9375rem;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0;
        letter-spacing: -0.01em;
    }

    @media (min-width: 1024px) {
        .sidebar-title {
            font-size: 1rem;
        }
    }

    @media (min-width: 1280px) {
        .sidebar-title {
            font-size: 1.0625rem;
        }
    }

    .voting-info {
        background: linear-gradient(135deg, var(--green-50) 0%, var(--green-100) 100%);
        border: 1px solid var(--green-300);
        border-radius: var(--radius-md);
        padding: 0.75rem;
        margin-bottom: 1rem;
        box-shadow: 0 1px 4px rgba(26, 191, 107, 0.08);
        transition: all 0.3s ease;
    }

    .voting-info:hover {
        border-color: var(--green-400);
        box-shadow: 0 2px 8px rgba(26, 191, 107, 0.12);
    }

    @media (min-width: 1280px) {
        .voting-info {
            padding: 0.875rem;
        }
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

    @media (min-width: 1280px) {
        .voting-info-icon {
            width: 1.125rem;
            height: 1.125rem;
        }
    }

    .voting-info-text {
        font-size: 0.75rem;
        color: var(--green-800);
        margin: 0;
        line-height: 1.5;
        font-weight: 500;
    }

    @media (min-width: 1024px) {
        .voting-info-text {
            font-size: 0.8125rem;
        }
    }

    @media (min-width: 1280px) {
        .voting-info-text {
            font-size: 0.875rem;
        }
    }

    /* Table Header */
    .table-header {
        display: grid;
        grid-template-columns: 1fr 50px 50px;
        gap: 0.375rem;
        padding: 0.625rem 0.75rem;
        background: linear-gradient(135deg, var(--green-100) 0%, var(--green-50) 100%);
        border: 1px solid var(--green-200);
        border-radius: var(--radius-md);
        margin-bottom: 0.5rem;
        font-size: 0.6875rem;
        font-weight: 700;
        color: var(--green-800);
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    @media (min-width: 1024px) {
        .table-header {
            grid-template-columns: 1fr 55px 55px;
            padding: 0.75rem 0.875rem;
            font-size: 0.75rem;
        }
    }

    @media (min-width: 1280px) {
        .table-header {
            grid-template-columns: 1fr 60px 60px;
            font-size: 0.8125rem;
        }
    }

    .table-header-cell {
        text-align: left;
        display: flex;
        align-items: center;
    }

    .table-header-cell.center {
        text-align: center;
        justify-content: center;
    }

    /* Suggestions List */
    .suggestions-list {
        overflow-y: visible;
        border: 1px solid var(--green-200);
        border-radius: var(--radius-md);
        background: white;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
    }

    .suggestion-item {
        display: grid;
        grid-template-columns: 1fr 50px 50px;
        gap: 0.375rem;
        padding: 0.625rem 0.75rem;
        border-bottom: 1px solid var(--gray-100);
        background: white;
        transition: all 0.25s ease;
        cursor: pointer;
        position: relative;
    }

    @media (min-width: 1024px) {
        .suggestion-item {
            grid-template-columns: 1fr 55px 55px;
            padding: 0.75rem 0.875rem;
        }
    }

    @media (min-width: 1280px) {
        .suggestion-item {
            grid-template-columns: 1fr 60px 60px;
            padding: 0.75rem 0.875rem;
        }
    }

    .suggestion-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 0;
        background: linear-gradient(180deg, var(--green-500) 0%, var(--green-600) 100%);
        transition: width 0.25s ease;
        border-radius: var(--radius-md) 0 0 var(--radius-md);
    }

    .suggestion-item:hover {
        background: linear-gradient(90deg, var(--green-50) 0%, white 100%);
        transform: translateX(1px);
    }

    .suggestion-item:hover::before {
        width: 3px;
    }

    .suggestion-item.active {
        background: linear-gradient(90deg, var(--green-100) 0%, var(--green-50) 100%);
        border-color: var(--green-200);
    }

    .suggestion-item.active::before {
        width: 3px;
    }

    .suggestion-item:last-child {
        border-bottom: none;
        border-radius: 0 0 var(--radius-md) var(--radius-md);
    }

    .suggestion-item:first-child {
        border-radius: var(--radius-md) var(--radius-md) 0 0;
    }

    .suggestion-name {
        min-width: 0;
        position: relative;
        z-index: 1;
    }

    .suggestion-info {
        min-width: 0;
        position: relative;
        z-index: 1;
    }

    .suggestion-title {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--gray-900);
        margin-bottom: 0.25rem;
        line-height: 1.3;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        transition: color 0.2s ease;
    }

    @media (min-width: 1024px) {
        .suggestion-title {
            font-size: 0.8125rem;
        }
    }

    @media (min-width: 1280px) {
        .suggestion-title {
            font-size: 0.875rem;
        }
    }

    .suggestion-item:hover .suggestion-title,
    .suggestion-item.active .suggestion-title {
        color: var(--green-700);
    }

    .suggestion-author {
        font-size: 0.625rem;
        color: var(--gray-500);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    @media (min-width: 1024px) {
        .suggestion-author {
            font-size: 0.6875rem;
        }
    }

    @media (min-width: 1280px) {
        .suggestion-author {
            font-size: 0.75rem;
        }
    }

    .suggestion-author::before {
        content: '👤';
        font-size: 0.7em;
    }

    .suggestion-stat {
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.125rem;
        font-size: 0.75rem;
        font-weight: 600;
        position: relative;
        z-index: 1;
    }

    @media (min-width: 1024px) {
        .suggestion-stat {
            font-size: 0.8125rem;
            gap: 0.25rem;
        }
    }

    @media (min-width: 1280px) {
        .suggestion-stat {
            font-size: 0.875rem;
        }
    }

    .suggestion-stat.liked {
        color: var(--red-500);
    }

    .suggestion-stat.liked:hover {
        color: var(--red-600);
    }

    .suggestion-stat.normal {
        color: var(--gray-600);
    }

    .suggestion-stat.comments {
        color: var(--blue-600);
    }

    .suggestion-stat.comments:hover {
        color: var(--blue-700);
    }

    /* Suggestion Cards */
    .suggestions-container {
        display: flex;
        flex-direction: column;
        gap: 1.75rem;
    }

    @media (min-width: 768px) {
        .suggestions-container {
            gap: 2rem;
        }
    }

    @media (min-width: 1024px) {
        .suggestions-container {
            gap: 2.5rem;
        }
    }

    .suggestion-card {
        overflow: hidden;
        position: relative;
        min-height: 350px;
        border-radius: var(--radius-xl);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        background: var(--gray-50);
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        border: 1px solid var(--gray-200);
    }

    @media (min-width: 768px) {
        .suggestion-card {
            min-height: 380px;
        }
    }

    @media (min-width: 1024px) {
        .suggestion-card {
            min-height: 400px;
        }
    }

    .suggestion-card:hover {
        transform: translateY(-6px) scale(1.01);
        box-shadow: 0 12px 28px rgba(26, 191, 107, 0.15);
        border-color: var(--green-200);
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
        margin-bottom: 1rem; /* match projects spacing */
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
        color: var(--blue-600);
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

    /* Use same like button styles as projects page for visual consistency */
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
        text-decoration: none !important;
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

    /* Detail button matches projects Detay appearance */
    .detail-button {
        color: rgba(255,255,255,0.9);
        background: var(--green-700);
        padding: 0.375rem 0.75rem;
        border-radius: var(--radius-md);
        font-size: 0.75rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        border: 1px solid rgba(255,255,255,0.5);
        backdrop-filter: blur(4px);
    }

    .detail-button:hover {
        transform: translateY(-1px);
        background: var(--green-800);
    }

    .detail-button-icon {
        width: 1rem;
        height: 1rem;
        color: currentColor;
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
        .content-grid {
            grid-template-columns: 260px 1fr;
            gap: 1.25rem;
        }

        .sidebar {
            position: static;
            height: auto;
            max-height: 60vh;
            overflow-y: auto;
            margin-bottom: 1.5rem;
            top: 0;
        }
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 2rem 0 !important;
        }

        .project-title {
            font-size: 1.75rem;
        }

        .project-subtitle {
            font-size: 1rem;
        }

        .header-title-wrapper {
            flex-direction: column;
            gap: 1rem;
        }

        .project-title-section {
            text-align: center;
        }

        .stats-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .stats-title {
            font-size: 1.125rem;
        }

        .stats-back-btn {
            align-self: center;
            padding: 0.625rem 1.125rem;
            font-size: 0.8125rem;
        }

        .stats-container {
            grid-template-columns: 1fr;
            gap: 1rem;
            max-width: 420px;
            margin: 0 auto;
        }

        .stat-card {
            padding: 1.5rem 1.25rem;
        }

        .stat-number {
            font-size: 2rem;
        }

        .stat-label {
            font-size: 0.875rem;
        }

        .content-grid {
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }

        .sidebar {
            position: static;
            max-height: 50vh;
            padding: 1rem;
            border-radius: var(--radius-md);
        }

        .sidebar-header {
            margin-bottom: 0.875rem;
            padding-bottom: 0.75rem;
        }

        .suggestions-list {
            max-height: 40vh;
        }

        .card-content {
            padding: 1.25rem;
        }

        .card-title {
            font-size: 1.5rem;
        }

        .card-actions {
            flex-direction: column;
            gap: 0.75rem;
        }

        .btn-like,
        .like-button,
        .detail-button {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 640px) {
        .user-container {
            padding: 0 0.875rem !important;
        }

        .stats-section {
            padding: 1rem 0;
        }

        .stats-header {
            padding: 0 0.5rem;
            margin-bottom: 1.25rem;
        }

        .stats-title {
            font-size: 1rem;
        }

        .stats-back-btn {
            padding: 0.5rem 0.875rem;
            font-size: 0.75rem;
        }

        .stats-back-btn svg {
            width: 0.875rem;
            height: 0.875rem;
        }

        .stats-container {
            padding: 0 0.5rem;
            gap: 0.875rem;
        }

        .stat-card {
            padding: 1.125rem 0.875rem;
            border-radius: var(--radius-lg);
        }

        .stat-icon {
            padding: 0.75rem;
        }

        .stat-icon svg {
            width: 1.75rem;
            height: 1.75rem;
        }

        .stat-number {
            font-size: 1.75rem;
        }

        .stat-label {
            font-size: 0.75rem;
        }

        .content-grid {
            grid-template-columns: 1fr !important;
            gap: 1.25rem !important;
        }

        .sidebar {
            position: static;
            order: 2;
            max-height: 45vh;
            padding: 0.875rem;
        }

        .sidebar-header {
            margin-bottom: 0.75rem;
            padding-bottom: 0.625rem;
        }

        .sidebar-title {
            font-size: 0.875rem;
        }

        .voting-info {
            padding: 0.625rem;
            margin-bottom: 0.875rem;
        }

        .voting-info-text {
            font-size: 0.6875rem;
        }

        .suggestions-container {
            order: 1;
        }

        .suggestion-item {
            grid-template-columns: 1fr 45px 45px;
            padding: 0.5rem 0.625rem;
        }

        .table-header {
            grid-template-columns: 1fr 45px 45px;
            padding: 0.5rem 0.625rem;
            font-size: 0.625rem;
        }

        .suggestion-title {
            font-size: 0.6875rem;
            margin-bottom: 0.25rem;
        }

        .suggestion-author {
            font-size: 0.5625rem;
        }

        .suggestion-stat {
            font-size: 0.6875rem;
        }
    }

    @media (max-width: 480px) {
        .project-title {
            font-size: 1.5rem;
        }

        .project-subtitle {
            font-size: 0.9375rem;
        }

        .sidebar {
            padding: 0.75rem;
            max-height: 40vh;
        }

        .sidebar-header {
            margin-bottom: 0.625rem;
            padding-bottom: 0.5rem;
        }

        .suggestion-item {
            grid-template-columns: 1fr 40px 40px;
            padding: 0.5rem;
        }

        .table-header {
            grid-template-columns: 1fr 40px 40px;
            padding: 0.5rem;
            font-size: 0.5625rem;
        }

        .suggestion-title {
            font-size: 0.625rem;
        }

        .suggestion-author {
            font-size: 0.5rem;
        }

        .suggestion-stat {
            font-size: 0.625rem;
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
                <svg class="project-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                </svg>
                <div class="project-title-section">
                    <h1 class="project-title">{{ $project->name }}</h1>
                    <p class="project-subtitle">{{ __('common.project_suggestions') }}</p>
                </div>
            </div>

            @php
                $totalSuggestions = $project->suggestions->count();
                $totalLikes = $project->suggestions->sum(function($suggestion) {
                    return $suggestion->likes->count();
                });
                $totalComments = $project->suggestions->sum(function($suggestion) {
                    return $suggestion->comments->count();
                });
            @endphp

            @if($totalSuggestions > 0)
            <!-- Statistics Section -->
            <div class="stats-section">
                <div class="stats-header">
                    <h2 class="stats-title">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width: 1.5rem; height: 1.5rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                        </svg>
                        {{ __('common.statistics') }}
                    </h2>
                    <a href="{{ route('user.projects') }}" class="stats-back-btn">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                        {{ __('common.back_to_projects') }}
                    </a>
                </div>

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
                    <p class="stat-label">{{ __('common.total_suggestions_count') }}</p>
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
                    <p class="stat-label">{{ __('common.total_likes_count') }}</p>
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
                    <p class="stat-label">{{ __('common.total_comments_count') }}</p>
                </div>
                </div>
                </div>
            @endif

            <!-- Project Timing Info -->
            @if($project->formatted_end_date || $project->getRemainingTime())
                @php
                    $remainingTime = $project->getRemainingTime();
                    $isExpired = $project->isExpired();
                @endphp
                <div class="stats-section" style="padding: 1.5rem 0; margin-bottom: 1rem;">
                    <div style="max-width: 600px; margin: 0 auto; text-center;">
                        <div style="display: inline-flex; align-items: center; gap: 0.75rem; padding: 1rem 2rem; border-radius: var(--radius-xl); background: {{ $isExpired ? 'rgba(239, 68, 68, 0.1)' : 'rgba(34, 197, 94, 0.1)' }}; border: 2px solid {{ $isExpired ? 'rgba(239, 68, 68, 0.3)' : 'rgba(34, 197, 94, 0.3)' }};">
                            <svg style="width: 1.5rem; height: 1.5rem; color: {{ $isExpired ? 'var(--red-600)' : 'var(--green-600)' }};" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <div style="text-align: left;">
                                @if($project->formatted_end_date)
                                    <div style="font-size: 0.875rem; color: {{ $isExpired ? 'var(--red-700)' : 'var(--green-700)' }}; font-weight: 500;">
                                        {{ __('common.project_end') }}: {{ $project->formatted_end_date }}
                                    </div>
                                @endif
                                <div style="font-size: 1rem; color: {{ $isExpired ? 'var(--red-600)' : 'var(--green-600)' }}; font-weight: 700;">
                                    @if($isExpired)
                                        {{ __('common.expired_voting_disabled') }}
                                    @elseif($remainingTime)
                                        {{ $remainingTime['formatted'] }} {{ __('common.time_remaining') }}
                                    @else
                                        {{ __('common.unlimited_time') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section><!-- Main Content Section -->
<div class="section-padding">
    <div class="user-container">
        @if($project->suggestions->count() > 0)
        <div class="content-grid">
            <!-- Sol Taraf: Suggestion Tree View (Sidebar) -->
            <div class="sidebar">
                <div class="sidebar-header">
                    <svg class="sidebar-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
                    </svg>
                    <h3 class="sidebar-title">{{ __('common.suggestion_list') }}</h3>
                </div>

                <div class="input-with-icon" style="margin-bottom: 0.75rem;">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0a7 7 0 10-9.9-9.9 7 7 0 009.9 9.9z"/>
                    </svg>
                    <input type="text" id="suggestion-search" placeholder="{{ __('common.search') }}">
                </div>

                <!-- Info about voting system -->
                <div style="background: var(--green-50); border: 1px solid var(--green-200); border-radius: var(--radius-md); padding: 0.625rem; margin-bottom: 0.875rem;">
                    <div style="display: flex; align-items: start; gap: 0.375rem;">
                        <svg style="width: 0.875rem; height: 0.875rem; color: var(--green-600); margin-top: 0.125rem; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                        </svg>
                        <div>
                            <p style="font-size: 0.6875rem; color: var(--green-700); margin: 0; line-height: 1.4; font-weight: 500;">
                                {{ __('common.voting_system_info') }}
                            </p>
                            <p style="font-size: 0.625rem; color: var(--green-600); margin: 0.25rem 0 0; line-height: 1.3;">
                                {{ __('common.voting_system_change_info') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="suggestions-list">
                    <!-- Project Node -->
                    <div class="table-header">
                        <div class="suggestion-title">{{ $project->name }}</div>
                        <div class="suggestion-likes" style="text-align: center;">
                            <svg style="width: 0.875rem; height: 0.875rem; display: inline-block; vertical-align: middle; margin-right: 0.25rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                            </svg>
                            <span style="font-size: 0.625rem;">Beğeni</span>
                        </div>
                        <div class="suggestion-comments" style="text-align: center;">
                            <svg style="width: 0.875rem; height: 0.875rem; display: inline-block; vertical-align: middle; margin-right: 0.25rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.627 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
                            </svg>
                            <span style="font-size: 0.625rem;">Yorum</span>
                        </div>
                    </div>

                    <!-- Suggestions -->
                    @foreach($project->suggestions->sortByDesc(function($suggestion) { return $suggestion->likes->count(); }) as $suggestion)
                    <div class="suggestion-item" data-title="{{ Str::lower($suggestion->title) }}" onclick="scrollToSuggestion({{ $suggestion->id }})">
                        <div class="suggestion-info">
                            <div class="suggestion-title">{{ Str::limit($suggestion->title, 35) }}</div>
                            @if($suggestion->createdBy)
                            <div class="suggestion-author">{{ $suggestion->createdBy->name }}</div>
                            @endif
                        </div>
                        <div class="suggestion-stat">
                            <span class="sidebar-like-count">{{ $suggestion->likes->count() }}</span>
                        </div>
                        <div class="suggestion-stat">
                            {{ $suggestion->comments->count() }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Sağ Taraf: Suggestion Cards (Suggestions Container) -->
            <div class="suggestions-container">
                <div class="d-flex" style="flex-direction: column; gap: 2rem;">
                    @foreach($project->suggestions->sortByDesc(function($suggestion) { return $suggestion->likes->count(); }) as $suggestion)
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

                            @if($suggestion->createdBy)
                            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                                <!-- profile/user icon (matching projects) -->
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem; color: rgba(255,255,255,0.8);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                                <span style="font-size: 0.875rem; color: rgba(255,255,255,0.9); text-shadow: 0 1px 2px rgba(0,0,0,0.5);">
                                    Öneren: {{ $suggestion->createdBy->name }}
                                </span>
                            </div>
                            @endif

                            @if($suggestion->description)
                            <p style="font-size: 1rem; color: rgba(255,255,255,0.9); margin-bottom: 1.5rem; text-shadow: 0 1px 2px rgba(0,0,0,0.5); line-height: 1.5;">
                                <!-- info-circle icon used in projects for descriptions -->
                                <svg style="width: 1rem; height: 1rem; display: inline; margin-right: 0.5rem; color: rgba(255,255,255,0.8);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round"></circle>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25h.008v.008H12V8.25zm0 2.25v4.5" />
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
                                    {{ $suggestion->likes->count() }} {{ __('common.likes') }}
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

                            <!-- Action Buttons (aligned with projects page) -->
                            <div style="display: flex; gap: 1rem; align-items: center;">
                                <!-- Like Button -->
                                @php
                                    $isProjectExpired = $project->isExpired();
                                @endphp
                                <button onclick="{{ $isProjectExpired ? 'showExpiredMessage()' : 'toggleLike(' . $suggestion->id . ')' }}"
                                        class="btn-like btn-like-large {{ Auth::check() && $suggestion->likes->where('user_id', Auth::id())->count() > 0 ? 'liked' : '' }} {{ $isProjectExpired ? 'expired' : '' }}"
                                        data-suggestion-id="{{ $suggestion->id }}"
                                        data-project-id="{{ $project->id }}"
                                        data-category="{{ $suggestion->category_id ?? 'default' }}"
                                        data-expired="{{ $isProjectExpired ? 'true' : 'false' }}"
                                        title="{{ $isProjectExpired ? 'Proje süresi dolmuş - Beğeni yapılamaz' : 'Bu kategoride sadece bir öneri beğenilebilir (Radio buton mantığı)' }}"
                                        {{ $isProjectExpired ? 'disabled' : '' }}
                                    <svg class="like-icon like-icon-large" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                    </svg>
                                    <span class="like-count">{{ $suggestion->likes->count() }}</span>
                                </button>

                                <!-- Detail Button -->
                                <a href="{{ route('user.suggestion.detail', $suggestion->id) }}" class="detail-button">
                                    <svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                    </svg>
                                    <span style="font-weight:600;">Detay</span>
                                </a>
                            </div>
                        </div>
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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 003 5.25v13.5A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
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
                    {{ __('common.waiting_for_first_projects') }}
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
document.addEventListener('DOMContentLoaded', () => {
    const suggestionSearchInput = document.getElementById('suggestion-search');
    const sidebarSuggestions = document.querySelectorAll('.suggestion-item');

    if (suggestionSearchInput && sidebarSuggestions.length) {
        suggestionSearchInput.addEventListener('input', function () {
            const term = this.value.toLowerCase();
            sidebarSuggestions.forEach(item => {
                const title = item.dataset.title || '';
                item.style.display = !term || title.includes(term) ? '' : 'none';
            });
        });
    }
});

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



// Show expired message for expired projects
function showExpiredMessage() {
    showMessage('Bu projenin süresi dolmuştur. Artık beğeni yapılamaz.', 'error');
}

// Toggle like with AJAX (Radio button logic: one per project category)
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
    const projectId = {{ $project->id }};

    // Get current suggestion's category from the data attributes
    const suggestionCategory = clickedButton.getAttribute('data-category') || 'default';

    // Find all buttons in the same category (radio button behavior)
    const allButtonsInCategory = document.querySelectorAll(`[data-category="${suggestionCategory}"]`);

    // Disable all non-expired buttons in this category during request
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
                btn.classList.remove('liked');
                const btnSuggestionId = btn.getAttribute('data-suggestion-id');

                // Reset heart icon to outline for all buttons in category
                const heartIcon = btn.querySelector('.like-button-icon') || btn.querySelector('.like-icon') || btn.querySelector('.like-icon-large');
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
                const heartIcon = clickedButton.querySelector('.like-button-icon') || clickedButton.querySelector('.like-icon') || clickedButton.querySelector('.like-icon-large');
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
                const heartIcon = clickedButton.querySelector('.like-button-icon') || clickedButton.querySelector('.like-icon') || clickedButton.querySelector('.like-icon-large');
                if (heartIcon) {
                    heartIcon.style.fill = 'none';
                }

                showMessage('{{ __('common.like_removed') }}', 'info');
            }

            // Update sidebar like counts
            updateSidebarStats();
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
                grid-template-columns: 1fr;
                gap: 1rem;
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
    const likeButtons = document.querySelectorAll('.like-button, .btn-like');
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
