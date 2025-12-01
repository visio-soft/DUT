@extends('filament.pages.user-panel.layout')

@section('title', $suggestion->title . ' - DUT Vote')

@section('content')
<!-- Header Section with Background -->
<section class="section-padding dynamic-background {{ ($hasBackgroundImages ?? false) ? '' : 'no-background-images' }}" style="padding: 2rem 0;">
    @if($hasBackgroundImages ?? false)
        @if($randomBackgroundImage)
            <!-- Single Random Background Image -->
            <div class="background-image-container">
                <img src="{{ $randomBackgroundImage }}"
                     alt="{{ __('common.suggestion_details') }}"
                     class="background-image-main"
                     loading="eager">
            </div>
        @endif
        <div class="background-image-overlay"></div>
    @endif

    <div class="user-container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb" style="position: relative; z-index: 3;">
            <a href="{{ route('user.index') }}">{{ __('common.home') }}</a>
            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
            <a href="{{ route('user.projects') }}">{{ __('common.projects') }}</a>
            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
            <span style="color: var(--gray-900);">{{ Str::limit($suggestion->title, 50) }}</span>
        </nav>
    </div>
</section>

<div class="detail-container">
    <div class="user-container">

        <div class="detail-card">
            <!-- Header -->
            <div class="detail-header">
                <div class="content-spacing-lg">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 0.5rem;">
                            <h1 class="detail-title" style="margin-bottom: 0;">{{ $suggestion->title }}</h1>
                            @if($suggestion->status)
                                <span style="padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em;
                                    {{ $suggestion->status->value === 'open' ? 'background: var(--green-100); color: var(--green-800); border: 1px solid var(--green-300);' : 'background: #fef2f2; color: #991b1b; border: 1px solid #fca5a5;' }}">
                                    {{ $suggestion->status->getLabel() }}
                                </span>
                            @endif
                        </div>

                        <!-- Project Info -->
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                            <span style="font-size: 0.875rem; color: var(--gray-500);">{{ __('common.project') }}:</span>
                            <span style="display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: var(--radius-xl); font-size: 0.875rem; background: var(--green-100); color: var(--green-800);">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                                </svg>
                                {{ $suggestion->category->name }}
                            </span>
                        </div>

                        <!-- Meta Information -->
                        <div class="detail-meta">
                            @if($suggestion->createdBy)
                            <div style="display: flex; align-items: center;">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                </svg>
                                {{ $suggestion->createdBy->name }}
                            </div>
                            @endif

                            @if($suggestion->estimated_duration)
                            <div style="display: flex; align-items: center;">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                                {{ $suggestion->estimated_duration }} {{ __('common.days') }}
                            </div>
                            @endif

                            <div style="display: flex; align-items: center;">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                </svg>
                                {{ $suggestion->created_at->format('d.m.Y') }}
                            </div>

                            @if($suggestion->category && $suggestion->category->end_datetime)
                                @php
                                    $remainingTime = $suggestion->category->getRemainingTime();
                                    $isExpired = $suggestion->category->isExpired();
                                @endphp
                                <div style="display: flex; align-items: center;">
                                    <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                    <span style="color: {{ $isExpired ? 'var(--red-600)' : 'var(--green-600)' }}; font-weight: 600;">
                                        @if($isExpired)
                                            {{ __('common.time_expired') }}
                                        @elseif($remainingTime)
                                            {{ $remainingTime['formatted'] }} {{ __('common.time_remaining') }}
                                        @else
                                            {{ __('common.unlimited') }}
                                        @endif
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>


                </div>

                <!-- Location Info -->
                @if($suggestion->address || $suggestion->district)
                <div class="detail-location">
                    <div style="display: flex; align-items: start; gap: 0.5rem;">
                        <svg style="width: 1.25rem; height: 1.25rem; color: var(--gray-400); margin-top: 0.125rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                        </svg>
                        <div>
                            <h4 style="font-weight: 500; color: var(--gray-900); margin-bottom: 0.25rem;">{{ __('common.location') }}</h4>
                            <p style="font-size: 0.875rem; color: var(--gray-600);">
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
            <div class="d-grid" style="grid-template-columns: 2fr 1fr; gap: 2rem;">
                <!-- Content Area -->
                <div class="detail-content">
                    <!-- Suggestion Image -->
                    @if($suggestion->getFirstMediaUrl('images'))
                    <div class="content-spacing" style="position: relative;">
                        <div style="position: relative; cursor: pointer; overflow: hidden; border-radius: var(--radius-lg);" onclick="openImageModal()">
                            <img id="suggestion-image" src="{{ $suggestion->getFirstMediaUrl('images') }}"
                                 alt="{{ $suggestion->title }}"
                                 style="width: 100%; height: 24rem; object-fit: cover; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); transition: transform 0.3s ease;"
                                 onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'; this.style.display='none'; this.parentElement.innerHTML='<div style=&quot;width: 100%; height: 24rem; background: linear-gradient(135deg, var(--green-100) 0%, var(--green-200) 100%); display: flex; align-items: center; justify-content: center; border-radius: var(--radius-lg);&quot;><div style=&quot;text-align: center;&quot;><svg style=&quot;width: 4rem; height: 4rem; color: var(--green-600); margin-bottom: 0.5rem;&quot; fill=&quot;currentColor&quot; viewBox=&quot;0 0 20 20&quot;><path fill-rule=&quot;evenodd&quot; d=&quot;M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z&quot; clip-rule=&quot;evenodd&quot;/></svg><p style=&quot;color: var(--green-700); font-size: 1rem;&quot;>{{ __('common.suggestion_image') }}</p></div></div>';"
                                 onmouseover="this.style.transform='scale(1.05)'; document.getElementById('zoom-overlay').style.opacity='1';"
                                 onmouseout="this.style.transform='scale(1)'; document.getElementById('zoom-overlay').style.opacity='0';">

                            <!-- Zoom Overlay -->
                            <div id="zoom-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease; border-radius: var(--radius-lg); pointer-events: none;">
                                <div style="background: rgba(255,255,255,0.9); padding: 0.75rem; border-radius: 50%; backdrop-filter: blur(8px);">
                                    <svg style="width: 2rem; height: 2rem; color: var(--gray-700);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Like Button (Small) - Positioned at bottom right of image -->
                        <div style="position: absolute; bottom: 1rem; right: 1rem; z-index: 2;">
                            @php
                                $userHasLikedInProject = false;
                                $userLikedSuggestionInProject = null;
                                if (Auth::check()) {
                                    $projectSuggestions = \App\Models\Suggestion::where('category_id', $suggestion->category_id)->get();
                                    foreach($projectSuggestions as $projectSuggestion) {
                                        if ($projectSuggestion->likes->where('user_id', Auth::id())->count() > 0) {
                                            $userHasLikedInProject = true;
                                            $userLikedSuggestionInProject = $projectSuggestion->id;
                                            break;
                                        }
                                    }
                                }
                            @endphp

                            @php
                                $isProjectExpired = $suggestion->category && $suggestion->category->isExpired();
                            @endphp
                            <button onclick="{{ $isProjectExpired ? 'showExpiredMessage()' : 'toggleLike(' . $suggestion->id . ')' }}"
                                    class="btn-like {{ Auth::check() && $suggestion->likes->where('user_id', Auth::id())->count() > 0 ? 'liked' : '' }} {{ $isProjectExpired ? 'expired' : '' }}"
                                    style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 600; background: {{ $isProjectExpired ? 'rgba(107, 114, 128, 0.5)' : 'rgba(239, 68, 68, 0.95)' }}; border: 2px solid {{ $isProjectExpired ? 'rgba(107, 114, 128, 0.3)' : '#dc2626' }}; border-radius: var(--radius-lg); color: {{ $isProjectExpired ? 'rgba(255, 255, 255, 0.5)' : 'white' }}; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.3); backdrop-filter: blur(8px); {{ $isProjectExpired ? 'cursor: not-allowed; opacity: 0.6;' : '' }}"
                                    data-suggestion-id="{{ $suggestion->id }}"
                                    data-category="{{ $suggestion->category_id ?? 'default' }}"
                                    data-expired="{{ $isProjectExpired ? 'true' : 'false' }}"
                                    title="{{ $isProjectExpired ? __('common.project_expired_tooltip') : __('common.suggestion_like_category_tooltip') }}"
                                    {{ $isProjectExpired ? 'disabled' : '' }}
                                    onmouseover="{{ $isProjectExpired ? '' : 'this.style.background=\'rgba(220, 38, 38, 0.95)\'; this.style.transform=\'translateY(-2px) scale(1.05)\'' }}"
                                    onmouseout="{{ $isProjectExpired ? '' : 'this.style.background=\'rgba(239, 68, 68, 0.95)\'; this.style.transform=\'translateY(0) scale(1)\'' }}">

                                <!-- Heart Icon -->
                                <svg class="like-icon" style="width: 1rem; height: 1rem; {{ Auth::check() && $suggestion->likes->where('user_id', Auth::id())->count() > 0 ? 'fill: currentColor;' : 'fill: none;' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                </svg>

                                <!-- Like Text and Count -->
                                <span>{{ __('common.like_button') }}</span>
                                <span class="like-count" style="background: rgba(255, 255, 255, 0.2); color: white; padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; min-width: 1.5rem; text-align: center;">{{ $suggestion->likes->count() }}</span>
                            </button>
                        </div>
                    </div>
                    @endif

                    <!-- Description -->
                    <div class="content-spacing">
                        <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--gray-900); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                            <svg style="width: 1.25rem; height: 1.25rem; color: var(--blue-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                            </svg>
                            {{ __('common.description') }}
                        </h3>
                        <div style="color: var(--gray-600); font-size: 1rem; line-height: 1.7;">
                            {!! nl2br(e($suggestion->description)) !!}
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div style="border-top: 1px solid var(--green-100); padding-top: 2rem;">
                        <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--gray-900); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                            <svg style="width: 1.25rem; height: 1.25rem; color: var(--blue-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z"/>
                            </svg>
                            {{ __('common.comments') }} ({{ $suggestion->approvedComments->count() }}{{ $userPendingComments->count() > 0 ? ' + ' . $userPendingComments->count() . ' ' . __('common.pending') : '' }})
                        </h3>

                        @if($suggestion->approvedComments->count() > 0 || $userPendingComments->count() > 0)
                            <div class="d-flex" style="flex-direction: column; gap: 1.5rem;">

                                {{-- Pending user comments --}}
                                @if($userPendingComments->count() > 0)
                                    @foreach($userPendingComments as $comment)
                                    <div class="comment-card" style="background: var(--yellow-50); border: 1px solid var(--yellow-200); position: relative;">
                                        <!-- Pending Badge -->
                                        <div style="position: absolute; top: 0.75rem; right: 0.75rem;">
                                            <span style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.75rem; background: var(--yellow-100); color: var(--yellow-800); font-size: 0.75rem; font-weight: 500; border-radius: var(--radius-xl); border: 1px solid var(--yellow-300);">
                                                <svg style="width: 0.75rem; height: 0.75rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>
                                                {{ __('common.pending_approval') }}
                                            </span>
                                        </div>

                                        <!-- Reply indicator if this is a reply -->
                                        @if($comment->parent_id)
                                        <div style="margin-bottom: 0.75rem; padding: 0.5rem 0.75rem; background: var(--blue-50); border: 1px solid var(--blue-200); border-radius: var(--radius-md); display: flex; align-items: center; gap: 0.5rem;">
                                            <svg style="width: 1rem; height: 1rem; color: var(--blue-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                            </svg>
                                            <span style="font-size: 0.85rem; color: var(--blue-700);">
                                                <strong>{{ $comment->parent->user->name ?? __('common.anonymous') }}</strong> {{ __('common.reply_to_user') }}
                                            </span>
                                        </div>
                                        @endif

                                        <div class="comment-header">
                                            <div class="comment-avatar" style="background: var(--yellow-300); color: var(--yellow-800);">
                                                {{ substr($comment->user->name ?? 'A', 0, 1) }}
                                            </div>
                                            <div>
                                                <h4 class="comment-author" style="color: var(--yellow-800);">{{ $comment->user->name ?? 'Anonim' }} <span style="font-weight: 400; font-size: 0.9em;">({{ __('common.you') }})</span></h4>
                                                <span class="comment-date" style="color: var(--yellow-600);">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <p class="comment-content" style="color: var(--yellow-800);">{{ $comment->comment }}</p>

                                        <div style="margin-top: 1rem; padding-top: 0.75rem; border-top: 1px solid var(--yellow-200); display: flex; align-items: center; gap: 0.5rem;">
                                            <svg style="width: 1rem; height: 1rem; color: var(--yellow-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                                            </svg>
                                            <small style="color: var(--yellow-700); font-size: 0.85rem;">
                                                {{ $comment->parent_id ? __('common.reply_pending') : __('common.comment_pending') }}
                                            </small>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif

                                {{-- Approved comments --}}
                                @foreach($suggestion->approvedComments as $comment)
                                <div class="comment-card">
                                    <div class="comment-header">
                                        <div class="comment-avatar">
                                            {{ substr($comment->user->name ?? 'A', 0, 1) }}
                                        </div>
                                        <div style="flex: 1;">
                                            <h4 class="comment-author">{{ $comment->user->name ?? 'Anonim' }}@if(Auth::check() && $comment->user_id === Auth::id()) <span style="font-weight: 400; font-size: 0.9em;">({{ __('common.you') }})</span>@endif</h4>
                                            <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <p class="comment-content">{{ $comment->comment }}</p>

                                    <!-- Comment Actions -->
                                    <div class="comment-actions" style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid var(--gray-100); display: flex; align-items: center; justify-content: space-between;">
                                        <div style="display: flex; align-items: center; gap: 1rem;">
                                            @auth
                                                <button
                                                    onclick="toggleCommentLike({{ $comment->id }})"
                                                    class="btn-like-comment {{ $comment->likes->where('user_id', Auth::id())->count() > 0 ? 'liked' : '' }}"
                                                    data-comment-id="{{ $comment->id }}"
                                                    style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.375rem 0.75rem; font-size: 0.875rem; background: {{ $comment->likes->where('user_id', Auth::id())->count() > 0 ? 'var(--red-50)' : 'var(--gray-50)' }}; color: {{ $comment->likes->where('user_id', Auth::id())->count() > 0 ? 'var(--red-600)' : 'var(--gray-600)' }}; border: 1px solid {{ $comment->likes->where('user_id', Auth::id())->count() > 0 ? 'var(--red-200)' : 'var(--gray-200)' }}; border-radius: var(--radius-md); transition: all 0.2s ease;"
                                                    onmouseover="this.style.transform='scale(1.05)'"
                                                    onmouseout="this.style.transform='scale(1)'">
                                                    <svg style="width: 0.875rem; height: 0.875rem; {{ $comment->likes->where('user_id', Auth::id())->count() > 0 ? 'fill: currentColor;' : 'fill: none;' }}" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                                    </svg>
                                                    <span class="like-count">{{ $comment->likes->count() }}</span>
                                                </button>

                                                <button
                                                    onclick="toggleReplyForm({{ $comment->id }})"
                                                    class="btn-reply"
                                                    style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.375rem 0.75rem; font-size: 0.875rem; background: var(--gray-100); color: var(--gray-700); border: 1px solid var(--gray-200); border-radius: var(--radius-md); transition: all 0.2s ease;"
                                                    onmouseover="this.style.background='var(--gray-200)'; this.style.color='var(--gray-800)'"
                                                    onmouseout="this.style.background='var(--gray-100)'; this.style.color='var(--gray-700)'">
                                                    <svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                                    </svg>
                                                    {{ __('common.reply') }}
                                                </button>
                                            @else
                                                <span style="font-size: 0.875rem; color: var(--gray-500);">
                                                    {{ __('common.login_for_like_reply') }} <a href="{{ route('user.login') }}" style="color: var(--blue-600);">{{ __('common.log_in') }}</a>
                                                </span>
                                            @endauth
                                        </div>

                                        <div style="display: flex; align-items: center; gap: 1rem;">
                                            @if($comment->likes->count() > 0)
                                                <span style="font-size: 0.875rem; color: var(--gray-500);">
                                                    {{ $comment->likes->count() }} {{ __('common.likes') }}
                                                </span>
                                            @endif
                                            @if($comment->approvedReplies->count() > 0)
                                                <span style="font-size: 0.875rem; color: var(--gray-500);">
                                                    {{ $comment->approvedReplies->count() }} {{ __('common.replies') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>                                    <!-- Reply Form (Initially Hidden) -->
                                    @auth
                                    <div id="reply-form-{{ $comment->id }}" class="reply-form" style="display: none; margin-top: 1rem; padding: 1rem; background: var(--gray-50); border-radius: var(--radius-md); border: 1px solid var(--gray-200);">
                                        <form onsubmit="submitReply(event, {{ $comment->id }})">
                                            @csrf
                                            <div style="margin-bottom: 0.75rem;">
                                                <textarea
                                                    name="comment"
                                                    id="reply-text-{{ $comment->id }}"
                                                    placeholder="{{ __('common.write_reply_to') }} {{ $comment->user->name ?? __('common.this_user') }}..."
                                                    required
                                                    maxlength="1000"
                                                    style="width: 100%; min-height: 80px; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: var(--radius-md); font-family: inherit; font-size: 0.9rem; resize: vertical; background: white;">
                                                </textarea>
                                                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem;">
                                                    <small style="color: var(--gray-500); font-size: 0.75rem;">
                                                        <span id="reply-char-count-{{ $comment->id }}">0</span>/1000 {{ __('common.characters_label') }}
                                                    </small>
                                                </div>
                                            </div>

                                            <div style="display: flex; gap: 0.5rem;">
                                                <button
                                                    type="submit"
                                                    class="btn-reply-submit"
                                                    style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; font-size: 0.875rem; background: var(--green-600); color: white; border: none; border-radius: var(--radius-md); transition: all 0.2s ease;"
                                                    onmouseover="this.style.background='var(--green-700)'"
                                                    onmouseout="this.style.background='var(--green-600)'">
                                                    <svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                                                    </svg>
                                                    {{ __('common.reply') }}
                                                </button>
                                                <button
                                                    type="button"
                                                    onclick="cancelReply({{ $comment->id }})"
                                                    style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; font-size: 0.875rem; background: var(--gray-200); color: var(--gray-700); border: none; border-radius: var(--radius-md); transition: all 0.2s ease;"
                                                    onmouseover="this.style.background='var(--gray-300)'"
                                                    onmouseout="this.style.background='var(--gray-200)'">
                                                    {{ __('common.cancel') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    @endauth

                                    <!-- Replies Section -->
                                    @if($comment->approvedReplies->count() > 0)
                                    <div class="replies-section" style="margin-top: 1.5rem; margin-left: 2.5rem; padding-left: 1rem; border-left: 3px solid var(--green-200);">
                                        @foreach($comment->approvedReplies as $reply)
                                        <div class="reply-card" style="margin-bottom: 1rem; padding: 1rem; background: var(--green-50); border-radius: var(--radius-md); border: 1px solid var(--green-200);">
                                            <div class="comment-header" style="margin-bottom: 0.5rem;">
                                                <div class="comment-avatar" style="width: 2rem; height: 2rem; background: var(--green-300); color: var(--green-800); font-size: 0.75rem;">
                                                    {{ substr($reply->user->name ?? 'A', 0, 1) }}
                                                </div>
                                                <div style="flex: 1;">
                                                    <h5 style="font-size: 0.9rem; font-weight: 600; color: var(--green-800); margin: 0;">{{ $reply->user->name ?? 'Anonim' }}@if(Auth::check() && $reply->user_id === Auth::id()) <span style="font-weight: 400; font-size: 0.85em;">({{ __('common.you') }})</span>@endif</h5>
                                                    <span style="font-size: 0.8rem; color: var(--green-600);">{{ $reply->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                            <p style="margin: 0 0 0.75rem 0; font-size: 0.9rem; color: var(--green-800); line-height: 1.5;">{{ $reply->comment }}</p>

                                            <!-- Reply Actions -->
                                            <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 0.5rem; border-top: 1px solid var(--green-200);">
                                                <div>
                                                    @auth
                                                        <button
                                                            onclick="toggleCommentLike({{ $reply->id }})"
                                                            class="btn-like-comment {{ $reply->likes->where('user_id', Auth::id())->count() > 0 ? 'liked' : '' }}"
                                                            data-comment-id="{{ $reply->id }}"
                                                            style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.5rem; font-size: 0.8rem; background: {{ $reply->likes->where('user_id', Auth::id())->count() > 0 ? 'var(--red-50)' : 'var(--green-100)' }}; color: {{ $reply->likes->where('user_id', Auth::id())->count() > 0 ? 'var(--red-600)' : 'var(--green-600)' }}; border: 1px solid {{ $reply->likes->where('user_id', Auth::id())->count() > 0 ? 'var(--red-200)' : 'var(--green-300)' }}; border-radius: var(--radius-sm); transition: all 0.2s ease;"
                                                            onmouseover="this.style.transform='scale(1.05)'"
                                                            onmouseout="this.style.transform='scale(1)'">
                                                            <svg style="width: 0.75rem; height: 0.75rem; {{ $reply->likes->where('user_id', Auth::id())->count() > 0 ? 'fill: currentColor;' : 'fill: none;' }}" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                                            </svg>
                                                            <span class="like-count">{{ $reply->likes->count() }}</span>
                                                        </button>
                                                    @else
                                                        <span style="font-size: 0.8rem; color: var(--green-600);">
                                                            <a href="{{ route('user.login') }}" style="color: var(--blue-600);">{{ __('common.log_in') }}</a>
                                                        </span>
                                                    @endauth
                                                </div>

                                                @if($reply->likes->count() > 0)
                                                    <span style="font-size: 0.8rem; color: var(--green-600);">
                                                        {{ $reply->likes->count() }} {{ __('common.likes') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Comment Form Section -->
                        @auth
                            <!-- Comment Form (Always Visible) -->
                            <div id="comment-form" style="margin-top: 2rem; padding: 1.5rem; background: var(--gray-50); border-radius: var(--radius-lg); border: 1px solid var(--gray-200);">
                                <h4 style="font-size: 1rem; font-weight: 600; color: var(--gray-900); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <svg style="width: 1.25rem; height: 1.25rem; color: var(--blue-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                    </svg>
                                    {{ __('common.write_your_comment') }}
                                </h4>

                                <form id="comment-submit-form" onsubmit="submitComment(event)">
                                    @csrf
                                    <div style="margin-bottom: 1rem;">
                                        <textarea
                                            name="comment"
                                            id="comment-text"
                                            placeholder="{{ __('common.comment_placeholder') }}"
                                            required
                                            maxlength="1000"
                                            style="width: 100%; min-height: 120px; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: var(--radius-md); font-family: inherit; font-size: 0.95rem; line-height: 1.5; resize: vertical; background: white;">
                                        </textarea>
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem;">
                                            <small style="color: var(--gray-500); font-size: 0.8rem;">
                                                <span id="char-count">0</span>/1000 {{ __('common.characters_label') }}
                                            </small>
                                            <small style="color: var(--gray-500); font-size: 0.8rem;">
                                                * {{ __('common.comments_visible_after_approval') }}
                                            </small>
                                        </div>
                                    </div>

                                    <div style="display: flex; gap: 0.75rem;">
                                        <button
                                            type="submit"
                                            class="btn btn-primary"
                                            style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                                            </svg>
                                            {{ __('common.submit_comment') }}
                                        </button>
                                        <button
                                            type="reset"
                                            onclick="resetCommentForm()"
                                            class="btn btn-secondary"
                                            style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                            </svg>
                                            {{ __('common.clear') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="detail-sidebar">
                    <!-- Stats -->
                    <div class="content-spacing">
                        <h4 style="font-weight: 600; color: var(--gray-900); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                            <svg style="width: 1.25rem; height: 1.25rem; color: var(--blue-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                            </svg>
                            {{ __('common.statistics') }}
                        </h4>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <span class="stat-label" style="display: flex; align-items: center; gap: 0.5rem;">
                                    <svg style="width: 1rem; height: 1rem; color: var(--red-500);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                    </svg>
                                    {{ __('common.total_likes') }}
                                </span>
                                <span class="stat-value">{{ $suggestion->likes->count() }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label" style="display: flex; align-items: center; gap: 0.5rem;">
                                    <svg style="width: 1rem; height: 1rem; color: var(--blue-500);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z"/>
                                    </svg>
                                    {{ __('common.total_comments') }}
                                </span>
                                <span class="stat-value">{{ $suggestion->approvedComments->count() }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label" style="display: flex; align-items: center; gap: 0.5rem;">
                                    <svg style="width: 1rem; height: 1rem; color: var(--gray-500);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                    </svg>
                                    {{ __('common.creation_date') }}
                                </span>
                                <span class="stat-value">{{ $suggestion->created_at->format('d.m.Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Likes -->
                    @if($suggestion->likes->count() > 0)
                    <div class="content-spacing">
                        <h4 style="font-weight: 600; color: var(--gray-900); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                            <svg style="width: 1.25rem; height: 1.25rem; color: var(--purple-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                            </svg>
                            {{ __('common.recent_likes') }}
                        </h4>
                        <div class="d-flex" style="flex-direction: column; gap: 0.5rem;">
                            @foreach($suggestion->likes->take(5) as $like)
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                                <div style="width: 1.5rem; height: 1.5rem; background: var(--green-300); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <span style="font-size: 0.75rem; font-weight: 500; color: var(--green-800);">
                                        {{ substr($like->user->name ?? 'A', 0, 1) }}
                                    </span>
                                </div>
                                <span style="color: var(--gray-600);">{{ $like->user->name ?? 'Anonim' }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="d-flex" style="flex-direction: column; gap: 0.75rem;">
                        <a href="{{ route('user.project.suggestions', $suggestion->project_id ?? $suggestion->category_id) }}"
                           class="btn btn-secondary" style="justify-content: center;">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/>
                            </svg>
                            {{ __('common.back_to_project') }}
                        </a>

                        <a href="{{ route('user.projects') }}"
                           class="btn btn-primary" style="justify-content: center;">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z"/>
                            </svg>
                            {{ __('common.all_projects_button') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Custom Success Modal -->
    <div id="success-modal" style="display: none; position: fixed; inset: 0; z-index: 9999; align-items: center; justify-content: center;">
        <!-- Backdrop -->
        <div id="modal-backdrop" style="position: absolute; inset: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); opacity: 0; transition: opacity 0.3s ease;"></div>

        <!-- Modal Content -->
        <div id="modal-content" style="position: relative; background: white; border-radius: 1.5rem; padding: 2.5rem; width: 100%; max-width: 400px; margin: 1rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); transform: scale(0.95); opacity: 0; transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);">
            <!-- Animated Icon -->
            <div style="width: 5rem; height: 5rem; margin: 0 auto 1.5rem; background: var(--green-50, #ecfdf5); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52" style="width: 3rem; height: 3rem; stroke: var(--green-600, #059669); stroke-width: 4; fill: none; stroke-linecap: round; stroke-linejoin: round; display: block; margin: 0 auto;">
                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                </svg>
            </div>

            <!-- Text -->
            <h3 id="modal-title" style="font-size: 1.5rem; font-weight: 700; color: var(--gray-900, #111827); text-align: center; margin-bottom: 0.5rem; font-family: inherit;">Teşekkür Ederiz!</h3>
            <p id="modal-message" style="font-size: 1rem; color: var(--gray-500, #6b7280); text-align: center; line-height: 1.5; margin-bottom: 2rem; font-family: inherit;">Geri bildiriminiz bizim için değerli.</p>

            <!-- Button -->
            <button onclick="closeSuccessModal()" style="width: 100%; padding: 0.875rem; background: var(--green-600, #059669); color: white; border: none; border-radius: 0.75rem; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);" onmouseover="this.style.background='var(--green-700, #047857)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.1)';" onmouseout="this.style.background='var(--green-600, #059669)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)';">
                Tamam
            </button>
        </div>
    </div>

    <style>
        /* SVG Animation Styles */
        .checkmark__circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 2;
            stroke-miterlimit: 10;
            stroke: var(--green-600, #059669);
            fill: none;
            /* Animation will be triggered by JS adding a class */
        }

        .checkmark__check {
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

    function showSuccessModal(title, message) {
        const modal = document.getElementById('success-modal');
        const backdrop = document.getElementById('modal-backdrop');
        const content = document.getElementById('modal-content');
        const circle = document.querySelector('.checkmark__circle');
        const check = document.querySelector('.checkmark__check');
        
        if (title) document.getElementById('modal-title').textContent = title;
        if (message) document.getElementById('modal-message').textContent = message;

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

        // Auto close
        if (modalTimer) clearTimeout(modalTimer);
        modalTimer = setTimeout(closeSuccessModal, 3000);
    }

    function closeSuccessModal() {
        const modal = document.getElementById('success-modal');
        const backdrop = document.getElementById('modal-backdrop');
        const content = document.getElementById('modal-content');

        if (!modal) return;

        backdrop.style.opacity = '0';
        content.style.opacity = '0';
        content.style.transform = 'scale(0.95)';

        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

const voteSwitchedMessageTemplate = @json(__('common.vote_switched_message'));
const genericErrorMessage = @json(__('common.generic_error'));

// Show expired message for expired projects
function showExpiredMessage() {
    showMessage('{{ __('common.project_expired_no_like') }}', 'error');
}

// Toggle like with AJAX (Radio button logic: one per category)
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

                // Reset heart icon if exists
                const heartIcon = btn.querySelector('.like-icon');
                if (heartIcon) {
                    heartIcon.style.fill = 'none';
                }

                // Update like counts if response contains data for other buttons in category
                const btnSuggestionId = btn.getAttribute('data-suggestion-id');
                if (response.all_likes && response.all_likes[btnSuggestionId] !== undefined) {
                    const btnLikeCount = btn.querySelector('.like-count');
                    if (btnLikeCount) {
                        btnLikeCount.textContent = response.all_likes[btnSuggestionId];
                    }
                }
            });

            // Update clicked button's like count
            likeCount.textContent = response.likes_count;

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
            let message = genericErrorMessage;
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

                // Update button styling for expired state
                clickedButton.style.background = 'rgba(107, 114, 128, 0.5)';
                clickedButton.style.borderColor = 'rgba(107, 114, 128, 0.3)';
                clickedButton.style.color = 'rgba(255, 255, 255, 0.5)';
                clickedButton.style.cursor = 'not-allowed';
                clickedButton.style.opacity = '0.6';
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

// Responsive layout adjustment
function adjustDetailLayout() {
    const detailGrid = document.querySelector('[style*="grid-template-columns: 2fr 1fr"]');
    if (detailGrid && window.innerWidth < 1024) {
        detailGrid.style.gridTemplateColumns = '1fr';
        detailGrid.style.gap = '1rem';
    } else if (detailGrid) {
        detailGrid.style.gridTemplateColumns = '2fr 1fr';
        detailGrid.style.gap = '2rem';
    }
}

// Call on load and resize
window.addEventListener('load', adjustDetailLayout);
window.addEventListener('resize', adjustDetailLayout);

// Image Modal Functions with Zoom
let isZoomed = false;
let zoomLevel = 1;
let panX = 0;
let panY = 0;
let isDragging = false;
let lastX = 0;
let lastY = 0;
let isFollowingMouse = false;
let mouseFollowTimeout = null;

function openImageModal() {
    const modal = document.getElementById('image-modal');
    const modalImage = document.getElementById('modal-image');
    const suggestionImage = document.getElementById('suggestion-image');

    if (modal && modalImage && suggestionImage) {
        modalImage.src = suggestionImage.src;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        // Reset zoom state
        resetZoom();

        // Animation
        setTimeout(() => {
            modal.style.opacity = '1';
            modalImage.style.transform = 'scale(1)';
        }, 50);

        // Add zoom event listeners
        setupZoomEvents();
    }
}

function resetZoom() {
    isZoomed = false;
    zoomLevel = 1;
    panX = 0;
    panY = 0;
    isFollowingMouse = false;

    // Clear mouse follow timeout
    if (mouseFollowTimeout) {
        clearTimeout(mouseFollowTimeout);
        mouseFollowTimeout = null;
    }

    const modalImage = document.getElementById('modal-image');
    const container = document.getElementById('image-container');

    if (modalImage && container) {
        modalImage.style.cursor = 'zoom-in';
        container.style.cursor = 'zoom-in';
        updateImageTransform();
    }
}

function setupZoomEvents() {
    const modalImage = document.getElementById('modal-image');
    const container = document.getElementById('image-container');

    if (!modalImage || !container) return;

    // Click to zoom/unzoom
    modalImage.addEventListener('click', handleImageClick);

    // Mouse events for desktop
    container.addEventListener('mousemove', handleMouseMove);
    container.addEventListener('mousedown', handleMouseDown);
    container.addEventListener('mouseup', handleMouseUp);
    container.addEventListener('mouseleave', handleMouseLeave);

    // Touch events for mobile
    container.addEventListener('touchstart', handleTouchStart, { passive: false });
    container.addEventListener('touchmove', handleTouchMove, { passive: false });
    container.addEventListener('touchend', handleTouchEnd, { passive: false });

    // Prevent context menu
    modalImage.addEventListener('contextmenu', (e) => e.preventDefault());

    // Auto-hide instructions after 3 seconds
    setTimeout(() => {
        const instructions = document.getElementById('zoom-instructions');
        if (instructions && !isZoomed) {
            instructions.style.opacity = '0';
            setTimeout(() => {
                if (instructions && !isZoomed) {
                    instructions.style.display = 'none';
                }
            }, 300);
        }
    }, 3000);
}

function handleImageClick(e) {
    e.stopPropagation();

    const instructions = document.getElementById('zoom-instructions');

    if (!isZoomed) {
        // Zoom in
        zoomLevel = 2.5;
        isZoomed = true;

        // Calculate zoom center based on click position
        const rect = e.target.getBoundingClientRect();
        const centerX = (e.clientX - rect.left) / rect.width;
        const centerY = (e.clientY - rect.top) / rect.height;

        // Adjust pan to center the clicked area
        panX = (0.5 - centerX) * (zoomLevel - 1) * rect.width;
        panY = (0.5 - centerY) * (zoomLevel - 1) * rect.height;

        e.target.style.cursor = 'zoom-out';
        document.getElementById('image-container').style.cursor = 'grab';

        // Enable mouse following immediately after zoom
        isFollowingMouse = true;

        // Update instructions
        if (instructions) {
            instructions.innerHTML = `
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 3.75H6A2.25 2.25 0 003.75 6v1.5M16.5 3.75H18A2.25 2.25 0 0120.25 6v1.5m0 9V18A2.25 2.25 0 0118 20.25h-1.5m-9 0H6A2.25 2.25 0 013.75 18v-1.5M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>{{ __('common.drag_instruction') }}</span>
                </div>
            `;
        }
    } else {
        // Zoom out
        resetZoom();

        // Reset instructions
        if (instructions) {
            instructions.innerHTML = `
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6"/>
                    </svg>
                    <span>{{ __('common.zoom_instruction') }}</span>
                </div>
            `;
        }
    }

    updateImageTransform();
}

function handleMouseMove(e) {
    if (!isZoomed) return;

    if (isDragging) {
        // Manual dragging mode - disable mouse follow
        isFollowingMouse = false;
        if (mouseFollowTimeout) {
            clearTimeout(mouseFollowTimeout);
        }

        const deltaX = e.clientX - lastX;
        const deltaY = e.clientY - lastY;

        panX += deltaX;
        panY += deltaY;

        lastX = e.clientX;
        lastY = e.clientY;

        updateImageTransform();
        e.preventDefault();
    } else if (isFollowingMouse) {
        // Mouse follow mode - smooth tracking
        followMousePosition(e);
    }
}

function followMousePosition(e) {
    const container = document.getElementById('image-container');
    const modalImage = document.getElementById('modal-image');

    if (!container || !modalImage) return;

    const containerRect = container.getBoundingClientRect();
    const imageRect = modalImage.getBoundingClientRect();

    // Calculate mouse position relative to container center
    const mouseCenterX = (e.clientX - containerRect.left) - (containerRect.width / 2);
    const mouseCenterY = (e.clientY - containerRect.top) - (containerRect.height / 2);

    // Calculate follow intensity (how much the image should move)
    const followIntensity = 0.4; // 40% of mouse movement for more responsive feel
    const maxPan = Math.min(containerRect.width, containerRect.height) * 0.5;

    // Apply smooth following with limits
    const targetPanX = -mouseCenterX * followIntensity;
    const targetPanY = -mouseCenterY * followIntensity;

    // Limit pan range
    panX = Math.max(-maxPan, Math.min(maxPan, targetPanX));
    panY = Math.max(-maxPan, Math.min(maxPan, targetPanY));

    updateImageTransform(true); // Use smooth transition
}

function handleMouseDown(e) {
    if (!isZoomed) return;

    // Disable mouse following when user starts dragging
    isFollowingMouse = false;
    if (mouseFollowTimeout) {
        clearTimeout(mouseFollowTimeout);
    }

    isDragging = true;
    lastX = e.clientX;
    lastY = e.clientY;

    const container = document.getElementById('image-container');
    if (container) {
        container.style.cursor = 'grabbing';
    }
    e.preventDefault();
}

function handleMouseUp(e) {
    if (isDragging) {
        isDragging = false;
        const container = document.getElementById('image-container');
        if (container && isZoomed) {
            container.style.cursor = 'grab';
        }

        // Re-enable mouse following after dragging ends (with shorter delay)
        if (isZoomed) {
            mouseFollowTimeout = setTimeout(() => {
                isFollowingMouse = true;
            }, 300); // Wait just 300ms before re-enabling mouse follow
        }
    }
}

function updateImageTransform(useSmooth = false) {
    const modalImage = document.getElementById('modal-image');
    if (modalImage) {
        modalImage.style.transform = `scale(${zoomLevel}) translate(${panX / zoomLevel}px, ${panY / zoomLevel}px)`;

        if (isDragging) {
            modalImage.style.transition = 'none';
        } else if (useSmooth || isFollowingMouse) {
            modalImage.style.transition = 'transform 0.08s ease-out'; // Even faster transition for more responsive mouse following
        } else {
            modalImage.style.transition = 'transform 0.4s ease';
        }
    }
}

// Touch event handlers for mobile
let lastTouchX = 0;
let lastTouchY = 0;

function handleTouchStart(e) {
    if (!isZoomed || e.touches.length !== 1) return;

    isDragging = true;
    lastTouchX = e.touches[0].clientX;
    lastTouchY = e.touches[0].clientY;
    e.preventDefault();
}

function handleTouchMove(e) {
    if (!isZoomed || !isDragging || e.touches.length !== 1) return;

    const deltaX = e.touches[0].clientX - lastTouchX;
    const deltaY = e.touches[0].clientY - lastTouchY;

    panX += deltaX;
    panY += deltaY;

    lastTouchX = e.touches[0].clientX;
    lastTouchY = e.touches[0].clientY;

    updateImageTransform();
    e.preventDefault();
}

function handleTouchEnd(e) {
    if (isDragging) {
        isDragging = false;
    }
}

function handleMouseLeave(e) {
    // Stop dragging if mouse leaves container
    if (isDragging) {
        isDragging = false;
        const container = document.getElementById('image-container');
        if (container && isZoomed) {
            container.style.cursor = 'grab';
        }
    }

    // Temporarily disable mouse following when mouse leaves
    if (isFollowingMouse) {
        isFollowingMouse = false;
        if (mouseFollowTimeout) {
            clearTimeout(mouseFollowTimeout);
        }

        // Smoothly return to center when mouse leaves
        panX *= 0.5;
        panY *= 0.5;
        updateImageTransform(true);
    }
}

function closeImageModal() {
    const modal = document.getElementById('image-modal');
    const modalImage = document.getElementById('modal-image');

    if (modal && modalImage) {
        // Clean up event listeners
        modalImage.removeEventListener('click', handleImageClick);

        const container = document.getElementById('image-container');
        if (container) {
            container.removeEventListener('mousemove', handleMouseMove);
            container.removeEventListener('mousedown', handleMouseDown);
            container.removeEventListener('mouseup', handleMouseUp);
            container.removeEventListener('mouseleave', handleMouseLeave);
        }

        modal.style.opacity = '0';
        modalImage.style.transform = 'scale(0.95)';

        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            resetZoom();
        }, 300);
    }
}

// Close modal when clicking outside the image
document.addEventListener('click', function(e) {
    const modal = document.getElementById('image-modal');
    const modalContent = document.querySelector('.modal-content');

    if (modal && e.target === modal && !modalContent.contains(e.target)) {
        closeImageModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Comment Form Functions
function toggleCommentForm() {
    // Since form is always visible now, just focus on textarea
    const textarea = document.getElementById('comment-text');
    if (textarea) {
        textarea.focus();
        textarea.scrollIntoView({ behavior: 'smooth' });
    }
}

function resetCommentForm() {
    const form = document.getElementById('comment-submit-form');
    const commentText = document.getElementById('comment-text');
    const charCount = document.getElementById('char-count');

    if (form) {
        form.reset();
    }
    if (commentText) {
        commentText.value = '';
    }
    if (charCount) {
        charCount.textContent = '0';
        charCount.style.color = 'var(--gray-500)';
    }
}

function submitComment(event) {
    event.preventDefault();

    const form = document.getElementById('comment-submit-form');
    const submitBtn = form.querySelector('button[type="submit"]');
    const commentText = document.getElementById('comment-text').value.trim();

    if (!commentText) {
        showMessage('{{ __('common.please_enter_comment') }}', 'error');
        return;
    }

    // Disable form
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg style="width: 1rem; height: 1rem; animation: spin 1s linear infinite;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg> {{ __('common.sending') }}';

    // Create FormData
    const formData = new FormData();
    formData.append('_token', document.querySelector('input[name="_token"]').value);
    formData.append('comment', commentText);

    // Submit via fetch
    fetch(`/suggestions/{{ $suggestion->id }}/comments`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('{{ __('common.comment_sent_success') }}', 'success');
            resetCommentForm();

            // Reload page to show the pending comment
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showMessage(data.message || '{{ __('common.comment_send_error') }}', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('{{ __('common.comment_send_error') }}', 'error');
    })
    .finally(() => {
        // Re-enable form
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg> {{ __('common.submit_comment') }}';
    });
}

// Character count for comment textarea
document.addEventListener('DOMContentLoaded', function() {
    const commentText = document.getElementById('comment-text');
    if (commentText) {
        commentText.addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('char-count').textContent = count;

            // Change color when approaching limit
            const charCountElement = document.getElementById('char-count');
            if (count > 900) {
                charCountElement.style.color = 'var(--red-600)';
            } else if (count > 800) {
                charCountElement.style.color = 'var(--yellow-600)';
            } else {
                charCountElement.style.color = 'var(--gray-500)';
            }
        });
    }

    // Add character count listeners for all reply textareas
    document.querySelectorAll('[id^="reply-text-"]').forEach(textarea => {
        const commentId = textarea.id.replace('reply-text-', '');
        textarea.addEventListener('input', function() {
            const count = this.value.length;
            const charCountElement = document.getElementById(`reply-char-count-${commentId}`);
            if (charCountElement) {
                charCountElement.textContent = count;

                // Change color when approaching limit
                if (count > 900) {
                    charCountElement.style.color = 'var(--red-600)';
                } else if (count > 800) {
                    charCountElement.style.color = 'var(--yellow-600)';
                } else {
                    charCountElement.style.color = 'var(--gray-500)';
                }
            }
        });
    });
});

// Reply Functions
function toggleReplyForm(commentId) {
    const form = document.getElementById(`reply-form-${commentId}`);

    if (form.style.display === 'none' || form.style.display === '') {
        // Hide all other reply forms
        document.querySelectorAll('.reply-form').forEach(otherForm => {
            if (otherForm !== form) {
                otherForm.style.display = 'none';
            }
        });

        form.style.display = 'block';
        // Focus on textarea
        setTimeout(() => {
            document.getElementById(`reply-text-${commentId}`).focus();
        }, 100);
    } else {
        form.style.display = 'none';
    }
}

function cancelReply(commentId) {
    const form = document.getElementById(`reply-form-${commentId}`);
    const textarea = document.getElementById(`reply-text-${commentId}`);
    const charCount = document.getElementById(`reply-char-count-${commentId}`);

    textarea.value = '';
    if (charCount) {
        charCount.textContent = '0';
        charCount.style.color = 'var(--gray-500)';
    }
    form.style.display = 'none';
}

function submitReply(event, commentId) {
    event.preventDefault();

    const form = event.target;
    const submitBtn = form.querySelector('.btn-reply-submit');
    const replyText = document.getElementById(`reply-text-${commentId}`).value.trim();

    if (!replyText) {
        showMessage('{{ __('common.please_enter_reply') }}', 'error');
        return;
    }

    // Disable form
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg style="width: 0.875rem; height: 0.875rem; animation: spin 1s linear infinite;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg> {{ __('common.sending') }}';

    // Create FormData
    const formData = new FormData();
    formData.append('_token', document.querySelector('input[name="_token"]').value);
    formData.append('comment', replyText);

    // Submit via fetch
    fetch(`/comments/${commentId}/reply`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('{{ __('common.reply_sent_success') }}', 'success');
            cancelReply(commentId);

            // Reload page to show the pending reply
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showMessage(data.message || '{{ __('common.reply_send_error') }}', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('{{ __('common.reply_send_error') }}', 'error');
    })
    .finally(() => {
        // Re-enable form
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg> {{ __('common.reply') }}';
    });
}

// Comment Like Functions
function toggleCommentLike(commentId) {
    @guest
        showMessage('{{ __('common.login_required_like') }}', 'error');
        setTimeout(() => {
            window.location.href = '{{ route('user.login') }}';
        }, 2000);
        return;
    @endguest

    const likeButton = document.querySelector(`[data-comment-id="${commentId}"]`);
    const likeCount = likeButton.querySelector('.like-count');
    const heartIcon = likeButton.querySelector('svg');

    // Disable button during request
    likeButton.disabled = true;
    likeButton.style.opacity = '0.7';

    // Submit via fetch
    fetch(`/comments/${commentId}/toggle-like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update like count
            likeCount.textContent = data.likes_count;

            if (data.liked) {
                // Add liked state
                likeButton.classList.add('liked');
                likeButton.style.background = 'var(--red-50)';
                likeButton.style.color = 'var(--red-600)';
                likeButton.style.borderColor = 'var(--red-200)';
                heartIcon.style.fill = 'currentColor';

                // showSuccessModal(
                //     'Teşekkürler!',
                //     'Yorumu beğendiğiniz için teşekkür ederiz.'
                // );
            } else {
                // Remove liked state
                likeButton.classList.remove('liked');
                likeButton.style.background = likeButton.closest('.reply-card') ? 'var(--green-100)' : 'var(--gray-50)';
                likeButton.style.color = likeButton.closest('.reply-card') ? 'var(--green-600)' : 'var(--gray-600)';
                likeButton.style.borderColor = likeButton.closest('.reply-card') ? 'var(--green-300)' : 'var(--gray-200)';
                heartIcon.style.fill = 'none';

                showMessage('{{ __('common.like_removed') }}', 'info');
            }
        } else {
            showMessage(data.message || '{{ __('common.like_error') }}', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('{{ __('common.like_error') }}', 'error');
    })
    .finally(() => {
        // Re-enable button
        likeButton.disabled = false;
        likeButton.style.opacity = '1';
    });
}
</script><!-- Image Modal -->
<div id="image-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.95); z-index: 9999; justify-content: center; align-items: center; opacity: 0; transition: opacity 0.3s ease; padding: 1rem;" onclick="closeImageModal()">
    <div class="modal-content" style="position: relative; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;" onclick="event.stopPropagation()">
        <!-- Close Button -->
        <button onclick="closeImageModal()" style="position: absolute; top: 1rem; right: 1rem; background: rgba(255,255,255,0.15); border: none; color: white; width: 3rem; height: 3rem; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; backdrop-filter: blur(10px); transition: all 0.3s ease; z-index: 10;" onmouseover="this.style.background='rgba(255,255,255,0.25)'; this.style.transform='scale(1.1)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'; this.style.transform='scale(1)'">
            <svg style="width: 1.75rem; height: 1.75rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Zoom Instructions -->
        <div id="zoom-instructions" style="position: absolute; top: 1rem; left: 1rem; background: rgba(0,0,0,0.7); color: white; padding: 0.75rem 1rem; border-radius: var(--radius-lg); font-size: 0.875rem; backdrop-filter: blur(10px); z-index: 10; opacity: 1; transition: opacity 0.3s ease;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6"/>
                </svg>
                <span>{{ __('common.zoom_instruction') }}</span>
            </div>
        </div>

        <!-- Image Container -->
        <div id="image-container" style="width: 100%; height: 85%; display: flex; align-items: center; justify-content: center; overflow: hidden; cursor: zoom-in; position: relative;">
            <img id="modal-image" src="" alt="{{ $suggestion->title ?? __('common.suggestion_image') }}" style="max-width: 95%; max-height: 95%; width: auto; height: auto; object-fit: contain; border-radius: var(--radius-xl); box-shadow: 0 25px 80px rgba(0,0,0,0.7); transform: scale(0.95); transition: transform 0.4s ease; cursor: zoom-in;">
        </div>

        <!-- Image Title -->
        <div style="background: rgba(55, 65, 81, 0.85); padding: 1.25rem 2.5rem; border-radius: var(--radius-xl); margin-top: 1.5rem; backdrop-filter: blur(12px); text-align: center; box-shadow: 0 15px 50px rgba(0,0,0,0.4); max-width: 90%; width: fit-content; border: 1px solid rgba(255,255,255,0.1);">
            <h3 style="color: white; font-size: 1.5rem; font-weight: 700; margin: 0; line-height: 1.2; display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                <svg style="width: 1.5rem; height: 1.5rem; color: #60a5fa;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.847a4.5 4.5 0 003.09 3.09L15.75 12l-2.847.813a4.5 4.5 0 00-3.09 3.09z"/>
                </svg>
                {{ $suggestion->title ?? __('common.suggestion_image') }}
            </h3>
            @if($suggestion->category)
            <p style="color: rgba(255,255,255,0.8); font-size: 1rem; margin: 0.75rem 0 0 0; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <svg style="width: 1rem; height: 1rem; color: #10b981;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                </svg>
                {{ $suggestion->category->name }}
            </p>
            @endif
        </div>
    </div>
</div>

<!-- Responsive Modal Styles -->
<style>
@media (max-width: 768px) {
    #image-modal {
        padding: 0.5rem !important;
    }

    #image-modal .modal-content {
        height: 100% !important;
    }

    #image-modal img {
        max-height: 85% !important;
        max-width: 90% !important;
        border-radius: var(--radius-lg) !important;
    }

    #image-modal button {
        width: 2.5rem !important;
        height: 2.5rem !important;
        top: 0.5rem !important;
        right: 0.5rem !important;
    }

    #image-modal button svg {
        width: 1.5rem !important;
        height: 1.5rem !important;
    }

    #zoom-instructions {
        top: 0.5rem !important;
        left: 0.5rem !important;
        font-size: 0.75rem !important;
        padding: 0.5rem 0.75rem !important;
    }

    #image-modal div:last-child {
        padding: 1rem 1.5rem !important;
        margin-top: 1rem !important;
        max-width: 95% !important;
        background: rgba(55, 65, 81, 0.9) !important;
    }

    #image-modal h3 {
        font-size: 1.125rem !important;
        flex-direction: column !important;
        gap: 0.5rem !important;
    }

    #image-modal h3 svg {
        width: 1.25rem !important;
        height: 1.25rem !important;
    }

    #image-modal p {
        font-size: 0.875rem !important;
        flex-direction: column !important;
        gap: 0.25rem !important;
    }

    #image-modal p svg {
        width: 0.875rem !important;
        height: 0.875rem !important;
    }
}

@media (min-width: 1200px) {
    #image-modal img {
        max-height: 90% !important;
        max-width: 85% !important;
    }
}

@media (min-width: 768px) {
    #image-modal img {
        max-width: 90% !important;
        max-height: 88% !important;
    }
}
</style>

    </div>
</div>
@endsection
