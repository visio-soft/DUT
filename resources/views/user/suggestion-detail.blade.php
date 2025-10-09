@extends('user.layout')

@section('title', $suggestion->title . ' - DUT Vote')

@section('content')
<!-- Header Section with Background -->
<section class="section-padding dynamic-background {{ ($hasBackgroundImages ?? false) ? '' : 'no-background-images' }}" style="padding: 2rem 0;">
    @if($hasBackgroundImages ?? false)
        @if($randomBackgroundImage)
            <!-- Single Random Background Image -->
            <div class="background-image-container">
                <img src="{{ $randomBackgroundImage }}"
                     alt="{{ __('user.suggestion_detail') }}"
                     class="background-image-main"
                     loading="eager">
            </div>
        @endif
        <div class="background-image-overlay"></div>
    @endif

    <div class="user-container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb" style="position: relative; z-index: 3;">
            <a href="{{ route('user.index') }}">{{ __('user.breadcrumb_home') }}</a>
            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
            <a href="{{ route('user.projects') }}">{{ __('user.breadcrumb_projects') }}</a>
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
                        <h1 class="detail-title">{{ $suggestion->title }}</h1>

                        <!-- Project Info -->
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                            <span style="font-size: 0.875rem; color: var(--gray-500);">Proje:</span>
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

                            @if($suggestion->budget)
                            <div style="display: flex; align-items: center;">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem; color: var(--green-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H4.5m2.25 0v3m0 0v.375c0 .621.504 1.125 1.125 1.125H9M3.75 4.5h.375c.621 0 1.125.504 1.125 1.125v.75c0 .621-.504 1.125-1.125 1.125H3.75V4.5ZM11.25 4.5h.375c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125H11.25V4.5ZM16.5 4.5h.375c.621 0 1.125.504 1.125 1.125v3c0 .621-.504 1.125-1.125 1.125H16.5V4.5Z"/>
                                </svg>
                                <span style="font-weight: 600; color: var(--green-700);">{{ number_format($suggestion->budget, 0) }} ₺</span>
                            </div>
                            @endif

                            @if($suggestion->estimated_duration)
                            <div style="display: flex; align-items: center;">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                                {{ $suggestion->estimated_duration }} gün
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
                                            Süre Dolmuş
                                        @elseif($remainingTime)
                                            {{ $remainingTime['formatted'] }} kaldı
                                        @else
                                            Süresiz
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
                            <h4 style="font-weight: 500; color: var(--gray-900); margin-bottom: 0.25rem;">Konum</h4>
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
                                 onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'; this.style.display='none'; this.parentElement.innerHTML='<div style=&quot;width: 100%; height: 24rem; background: linear-gradient(135deg, var(--green-100) 0%, var(--green-200) 100%); display: flex; align-items: center; justify-content: center; border-radius: var(--radius-lg);&quot;><div style=&quot;text-align: center;&quot;><svg style=&quot;width: 4rem; height: 4rem; color: var(--green-600); margin-bottom: 0.5rem;&quot; fill=&quot;currentColor&quot; viewBox=&quot;0 0 20 20&quot;><path fill-rule=&quot;evenodd&quot; d=&quot;M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z&quot; clip-rule=&quot;evenodd&quot;/></svg><p style=&quot;color: var(--green-700); font-size: 1rem;&quot;>{{ __('user.suggestion_image') }}</p></div></div>';"
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
                                    $projectSuggestions = \App\Models\Oneri::where('category_id', $suggestion->category_id)->get();
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
                                    title="{{ $isProjectExpired ? __('user.expired_no_likes') : __('user.only_one_like_per_category') }}"
                                    {{ $isProjectExpired ? 'disabled' : '' }}
                                    onmouseover="{{ $isProjectExpired ? '' : 'this.style.background=\'rgba(220, 38, 38, 0.95)\'; this.style.transform=\'translateY(-2px) scale(1.05)\'' }}"
                                    onmouseout="{{ $isProjectExpired ? '' : 'this.style.background=\'rgba(239, 68, 68, 0.95)\'; this.style.transform=\'translateY(0) scale(1)\'' }}">

                                <!-- Heart Icon -->
                                <svg class="like-icon" style="width: 1rem; height: 1rem; {{ Auth::check() && $suggestion->likes->where('user_id', Auth::id())->count() > 0 ? 'fill: currentColor;' : 'fill: none;' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                </svg>

                                <!-- Like Text and Count -->
                                <span>{{ __('user.like') }}</span>
                                <span class="like-count" style="background: rgba(255, 255, 255, 0.2); color: white; padding: 0.125rem 0.5rem; border-radius: var(--radius-full); font-size: 0.75rem; font-weight: 700; min-width: 1.5rem; text-align: center;">{{ $suggestion->likes->count() }}</span>
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
                            Açıklama
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
                            {{ __('user.comments_section') }} ({{ $suggestion->approvedComments->count() }}{{ $userPendingComments->count() > 0 ? ' + ' . $userPendingComments->count() . ' ' . __('user.pending') : '' }})
                        </h3>

                        @if($suggestion->approvedComments->count() > 0 || $userPendingComments->count() > 0)
                            <div class="d-flex" style="flex-direction: column; gap: 1.5rem;">

                                {{-- Kullanıcının onaylanmamış yorumları --}}
                                @if($userPendingComments->count() > 0)
                                    @foreach($userPendingComments as $comment)
                                    <div class="comment-card" style="background: var(--yellow-50); border: 1px solid var(--yellow-200); position: relative;">
                                        <!-- Pending Badge -->
                                        <div style="position: absolute; top: 0.75rem; right: 0.75rem;">
                                            <span style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.75rem; background: var(--yellow-100); color: var(--yellow-800); font-size: 0.75rem; font-weight: 500; border-radius: var(--radius-xl); border: 1px solid var(--yellow-300);">
                                                <svg style="width: 0.75rem; height: 0.75rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>
                                                Onay Bekliyor
                                            </span>
                                        </div>

                                        <!-- Reply indicator if this is a reply -->
                                        @if($comment->parent_id)
                                        <div style="margin-bottom: 0.75rem; padding: 0.5rem 0.75rem; background: var(--blue-50); border: 1px solid var(--blue-200); border-radius: var(--radius-md); display: flex; align-items: center; gap: 0.5rem;">
                                            <svg style="width: 1rem; height: 1rem; color: var(--blue-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                            </svg>
                                            <span style="font-size: 0.85rem; color: var(--blue-700);">
                                                <strong>{{ $comment->parent->user->name ?? __('user.anonymous') }}</strong> {{ __('user.reply_to_user') }}
                                            </span>
                                        </div>
                                        @endif

                                        <div class="comment-header">
                                            <div class="comment-avatar" style="background: var(--yellow-300); color: var(--yellow-800);">
                                                {{ substr($comment->user->name ?? 'A', 0, 1) }}
                                            </div>
                                            <div>
                                                <h4 class="comment-author" style="color: var(--yellow-800);">{{ $comment->user->name ?? 'Anonim' }} <span style="font-weight: 400; font-size: 0.9em;">(Siz)</span></h4>
                                                <span class="comment-date" style="color: var(--yellow-600);">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <p class="comment-content" style="color: var(--yellow-800);">{{ $comment->comment }}</p>

                                        <div style="margin-top: 1rem; padding-top: 0.75rem; border-top: 1px solid var(--yellow-200); display: flex; align-items: center; gap: 0.5rem;">
                                            <svg style="width: 1rem; height: 1rem; color: var(--yellow-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                                            </svg>
                                            <small style="color: var(--yellow-700); font-size: 0.85rem;">
                                                {{ __('user.comment_pending_approval', ['type' => $comment->parent_id ? __('user.reply') : __('user.comment')]) }}
                                            </small>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif

                                {{-- Onaylanmış yorumlar --}}
                                @foreach($suggestion->approvedComments as $comment)
                                <div class="comment-card">
                                    <div class="comment-header">
                                        <div class="comment-avatar">
                                            {{ substr($comment->user->name ?? 'A', 0, 1) }}
                                        </div>
                                        <div style="flex: 1;">
                                            <h4 class="comment-author">{{ $comment->user->name ?? 'Anonim' }}@if(Auth::check() && $comment->user_id === Auth::id()) <span style="font-weight: 400; font-size: 0.9em;">(Siz)</span>@endif</h4>
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
                                                    {{ __('user.reply') }}
                                                </button>
                                            @else
                                                <span style="font-size: 0.875rem; color: var(--gray-500);">
                                                    <a href="{{ route('user.login') }}" style="color: var(--blue-600);">{{ __('user.login') }}</a> • {{ __('user.login_to_like_reply') }}
                                                </span>
                                            @endauth
                                        </div>

                                        <div style="display: flex; align-items: center; gap: 1rem;">
                                            @if($comment->likes->count() > 0)
                                                <span style="font-size: 0.875rem; color: var(--gray-500);">
                                                    {{ $comment->likes->count() }} {{ __('user.likes') }}
                                                </span>
                                            @endif
                                            @if($comment->approvedReplies->count() > 0)
                                                <span style="font-size: 0.875rem; color: var(--gray-500);">
                                                    {{ $comment->approvedReplies->count() }} {{ __('user.replies') }}
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
                                                    placeholder="{{ __('user.reply_to_user_placeholder', ['name' => $comment->user->name ?? __('user.this_user')]) }}"
                                                    required
                                                    maxlength="1000"
                                                    style="width: 100%; min-height: 80px; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: var(--radius-md); font-family: inherit; font-size: 0.9rem; resize: vertical; background: white;">
                                                </textarea>
                                                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem;">
                                                    <small style="color: var(--gray-500); font-size: 0.75rem;">
                                                        <span id="reply-char-count-{{ $comment->id }}">0</span>/1000 {{ __('user.characters') }}
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
                                                    Cevapla
                                                </button>
                                                <button
                                                    type="button"
                                                    onclick="cancelReply({{ $comment->id }})"
                                                    style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; font-size: 0.875rem; background: var(--gray-200); color: var(--gray-700); border: none; border-radius: var(--radius-md); transition: all 0.2s ease;"
                                                    onmouseover="this.style.background='var(--gray-300)'"
                                                    onmouseout="this.style.background='var(--gray-200)'">
                                                    İptal
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
                                                    <h5 style="font-size: 0.9rem; font-weight: 600; color: var(--green-800); margin: 0;">{{ $reply->user->name ?? 'Anonim' }}@if(Auth::check() && $reply->user_id === Auth::id()) <span style="font-weight: 400; font-size: 0.85em;">(Siz)</span>@endif</h5>
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
                                                            <a href="{{ route('user.login') }}" style="color: var(--blue-600);">{{ __('user.login') }}</a>
                                                        </span>
                                                    @endauth
                                                </div>

                                                @if($reply->likes->count() > 0)
                                                    <span style="font-size: 0.8rem; color: var(--green-600);">
                                                        {{ $reply->likes->count() }} {{ __('user.likes') }}
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
                        @else
                            <!-- Empty Comments State -->
                            <div style="text-align: center; padding: 3rem 2rem; background: var(--gray-50); border-radius: var(--radius-lg); border: 2px dashed var(--gray-200);">
                                <div style="margin-bottom: 1.5rem;">
                                    <svg style="width: 4rem; height: 4rem; margin: 0 auto; color: var(--gray-400);" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/>
                                    </svg>
                                </div>
                                <h4 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-700); margin-bottom: 0.75rem;">
                                    Henüz bir yorum yok
                                </h4>
                                <p style="color: var(--gray-500); margin-bottom: 2rem; font-size: 0.95rem;">
                                    Bu öneri hakkındaki düşüncelerinizi paylaşın. İlk yorumu yapan siz olun! 💭
                                </p>
                                @auth
                                    <button
                                        onclick="document.getElementById('comment-text').focus(); document.getElementById('comment-text').scrollIntoView({behavior: 'smooth'});"
                                        class="btn btn-primary"
                                        style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem;">
                                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                        </svg>
                                        İlk Yorumu Ekle
                                    </button>
                                @else
                                    <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                                        <p style="color: var(--gray-600); font-size: 0.9rem; margin: 0;">
                                            Yorum yapabilmek için giriş yapmalısınız
                                        </p>
                                        <a href="{{ route('user.login') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                                            </svg>
                                            Giriş Yap
                                        </a>
                                    </div>
                                @endauth
                            </div>
                        @endif

                        <!-- Comment Form Section -->
                        @auth
                            <!-- Comment Form (Always Visible) -->
                            <div id="comment-form" style="margin-top: 2rem; padding: 1.5rem; background: var(--gray-50); border-radius: var(--radius-lg); border: 1px solid var(--gray-200);
                                <h4 style="font-size: 1rem; font-weight: 600; color: var(--gray-900); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <svg style="width: 1.25rem; height: 1.25rem; color: var(--blue-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                    </svg>
                                    {{ __('user.write_your_comment') }}
                                </h4>

                                <form id="comment-submit-form" onsubmit="submitComment(event)">
                                    @csrf
                                    <div style="margin-bottom: 1rem;">
                                        <textarea
                                            name="comment"
                                            id="comment-text"
                                            placeholder="Öneriniz hakkındaki düşüncelerinizi buraya yazın..."
                                            required
                                            maxlength="1000"
                                            style="width: 100%; min-height: 120px; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: var(--radius-md); font-family: inherit; font-size: 0.95rem; line-height: 1.5; resize: vertical; background: white;">
                                        </textarea>
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem;">
                                            <small style="color: var(--gray-500); font-size: 0.8rem;">
                                                <span id="char-count">0</span>/1000 {{ __('user.characters') }}
                                            </small>
                                            <small style="color: var(--gray-500); font-size: 0.8rem;">
                                                * Yorumlar onaylandıktan sonra görüntülenir
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
                                            {{ __('user.send_comment') }}
                                        </button>
                                        <button
                                            type="reset"
                                            onclick="resetCommentForm()"
                                            class="btn btn-secondary"
                                            style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                            </svg>
                                            Temizle
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
                            İstatistikler
                        </h4>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <span class="stat-label" style="display: flex; align-items: center; gap: 0.5rem;">
                                    <svg style="width: 1rem; height: 1rem; color: var(--red-500);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                    </svg>
                                    {{ __('user.total_likes') }}
                                </span>
                                <span class="stat-value">{{ $suggestion->likes->count() }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label" style="display: flex; align-items: center; gap: 0.5rem;">
                                    <svg style="width: 1rem; height: 1rem; color: var(--blue-500);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z"/>
                                    </svg>
                                    {{ __('user.total_comments') }}
                                </span>
                                <span class="stat-value">{{ $suggestion->approvedComments->count() }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label" style="display: flex; align-items: center; gap: 0.5rem;">
                                    <svg style="width: 1rem; height: 1rem; color: var(--gray-500);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                    </svg>
                                    Oluşturma Tarihi
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
                            {{ __('user.last_liked_by') }}
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
                        <a href="{{ route('user.project.suggestions', $suggestion->category_id) }}"
                           class="btn btn-secondary" style="justify-content: center;">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/>
                            </svg>
                            Projeye Dön
                        </a>

                        <a href="{{ route('user.projects') }}"
                           class="btn btn-primary" style="justify-content: center;">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z"/>
                            </svg>
                            Tüm Projeler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Show expired message for expired projects
function showExpiredMessage() {
    showMessage('{{ __('user.project_expired_no_likes') }}', 'error');
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
        showMessage('Beğeni yapmak için giriş yapmanız gerekiyor.', 'error');
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
                    showMessage(`✓ {{ __('user.selection_changed_from') }} "${response.switched_from}" {{ __('user.to') }} "${response.current_title}".`, 'success');
                } else {
                    showMessage('✓ {{ __('user.suggestion_liked') }}', 'success');
                }
            } else {
                clickedButton.classList.remove('liked');

                // Reset heart icon to outline
                const heartIcon = clickedButton.querySelector('.like-icon');
                if (heartIcon) {
                    heartIcon.style.fill = 'none';
                }

                showMessage('{{ __('user.like_removed') }}', 'info');
            }
        },
        error: function(xhr) {
            let message = '{{ __('user.error_occurred') }}.';
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
                    <span>Fareyi hareket ettir • Sürükle • Çık</span>
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
                    <span>Yakınlaştırmak için resme tıklayın</span>
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
        showMessage('{{ __('user.please_enter_comment') }}', 'error');
        return;
    }

    // Disable form
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg style="width: 1rem; height: 1rem; animation: spin 1s linear infinite;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg> Gönderiliyor...';

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
            showMessage('{{ __('user.comment_sent_success') }}', 'success');
            resetCommentForm();

            // Reload page to show the pending comment
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showMessage(data.message || 'Yorum gönderilirken bir hata oluştu.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Yorum gönderilirken bir hata oluştu.', 'error');
    })
    .finally(() => {
        // Re-enable form
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg> {{ __('user.send_comment') }}';
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
        showMessage('{{ __('user.please_enter_reply') }}', 'error');
        return;
    }

    // Disable form
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg style="width: 0.875rem; height: 0.875rem; animation: spin 1s linear infinite;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg> Gönderiliyor...';

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
            showMessage('Cevabınız başarıyla gönderildi. Onaylandıktan sonra görüntülenecektir.', 'success');
            cancelReply(commentId);

            // Reload page to show the pending reply
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showMessage(data.message || 'Cevap gönderilirken bir hata oluştu.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Cevap gönderilirken bir hata oluştu.', 'error');
    })
    .finally(() => {
        // Re-enable form
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg> Cevapla';
    });
}

// Comment Like Functions
function toggleCommentLike(commentId) {
    @guest
        showMessage('Beğeni yapmak için giriş yapmanız gerekiyor.', 'error');
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

                showMessage('{{ __('user.comment_liked') }}', 'success');
            } else {
                // Remove liked state
                likeButton.classList.remove('liked');
                likeButton.style.background = likeButton.closest('.reply-card') ? 'var(--green-100)' : 'var(--gray-50)';
                likeButton.style.color = likeButton.closest('.reply-card') ? 'var(--green-600)' : 'var(--gray-600)';
                likeButton.style.borderColor = likeButton.closest('.reply-card') ? 'var(--green-300)' : 'var(--gray-200)';
                heartIcon.style.fill = 'none';

                showMessage('Beğeni kaldırıldı.', 'info');
            }
        } else {
            showMessage(data.message || 'Beğeni işlemi sırasında bir hata oluştu.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Beğeni işlemi sırasında bir hata oluştu.', 'error');
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
                <span>Yakınlaştırmak için resme tıklayın</span>
            </div>
        </div>

        <!-- Image Container -->
        <div id="image-container" style="width: 100%; height: 85%; display: flex; align-items: center; justify-content: center; overflow: hidden; cursor: zoom-in; position: relative;">
            <img id="modal-image" src="" alt="{{ $suggestion->title ?? 'Öneri Görseli' }}" style="max-width: 95%; max-height: 95%; width: auto; height: auto; object-fit: contain; border-radius: var(--radius-xl); box-shadow: 0 25px 80px rgba(0,0,0,0.7); transform: scale(0.95); transition: transform 0.4s ease; cursor: zoom-in;">
        </div>

        <!-- Image Title -->
        <div style="background: rgba(55, 65, 81, 0.85); padding: 1.25rem 2.5rem; border-radius: var(--radius-xl); margin-top: 1.5rem; backdrop-filter: blur(12px); text-align: center; box-shadow: 0 15px 50px rgba(0,0,0,0.4); max-width: 90%; width: fit-content; border: 1px solid rgba(255,255,255,0.1);">
            <h3 style="color: white; font-size: 1.5rem; font-weight: 700; margin: 0; line-height: 1.2; display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                <svg style="width: 1.5rem; height: 1.5rem; color: #60a5fa;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.847a4.5 4.5 0 003.09 3.09L15.75 12l-2.847.813a4.5 4.5 0 00-3.09 3.09z"/>
                </svg>
                {{ $suggestion->title ?? 'Öneri Görseli' }}
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
