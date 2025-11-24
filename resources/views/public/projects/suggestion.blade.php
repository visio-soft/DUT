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
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        {{ $suggestion->title }}
                    </h1>
                    <p class="text-gray-600 mt-1">
                        <a href="{{ route('public.projects.index') }}" class="hover:text-blue-600">{{ __('common.project_categories') }}</a>
                        <span class="mx-2">/</span>
                        <span class="text-blue-600">{{ $suggestion->category->name ?? __('common.uncategorized') }}</span>
                        <span class="mx-2">/</span>
                        <span class="text-green-600">{{ __('common.suggestion') }}</span>
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
                <!-- Suggestion Details Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <!-- Suggestion Image -->
                    @if($suggestion->hasMedia('images'))
                        <div class="mb-6">
                            <img src="{{ $suggestion->getFirstMediaUrl('images') }}"
                                 alt="{{ $suggestion->title }}"
                                 class="w-full h-64 object-cover rounded-lg">
                        </div>
                    @endif

                    <!-- Suggestion Description -->
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('common.suggestion_details') }}</h3>
                        <div class="prose prose-sm max-w-none">
                            {!! nl2br(e($suggestion->description)) !!}
                        </div>
                    </div>

                    <!-- Suggestion Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                        @if($suggestion->estimated_duration)
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-gray-600">{{ __('common.estimated_duration') }}:</span>
                                <span class="font-medium">{{ $suggestion->estimated_duration }} {{ __('common.days') }}</span>
                            </div>
                        @endif

                        @if($suggestion->city)
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-sm text-gray-600">{{ __('common.location') }}:</span>
                                <span class="font-medium">
                                    {{ $suggestion->city }}
                                    @if($suggestion->district), {{ $suggestion->district }}@endif
                                    @if($suggestion->neighborhood), {{ $suggestion->neighborhood }}@endif
                                </span>
                            </div>
                        @endif

                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">{{ __('common.creation') }}:</span>
                            <span class="font-medium">{{ $suggestion->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Related Project -->
                @if($suggestion->project)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ __('common.related_project') }}
                        </h3>

                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 mb-2">
                                        <a href="{{ route('public.projects.show', $suggestion->project->id) }}"
                                           class="hover:text-blue-600 transition-colors">
                                            {{ $suggestion->project->title }}
                                        </a>
                                    </h4>
                                    @if($suggestion->project->description)
                                        <p class="text-sm text-gray-600 mb-2">
                                            {{ Str::limit($suggestion->project->description, 200) }}
                                        </p>
                                    @endif
                                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                                        @if($suggestion->project->createdBy)
                                        <span>{{ $suggestion->project->createdBy->name }}</span>
                                        @endif
                                        <span>{{ $suggestion->project->created_at->format('d.m.Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Comments Section -->
                @if($suggestion->approvedComments->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            {{ __('common.comments') }} ({{ $suggestion->approvedComments->count() }})
                        </h3>

                        <div class="space-y-4">
                            @foreach($suggestion->approvedComments as $comment)
                                <div class="border-l-4 border-blue-200 pl-4 py-2">
                                    <div class="flex items-start justify-between mb-2">
                                        <span class="font-medium text-gray-900">{{ $comment->user ? $comment->user->name : __('common.anonymous') }}</span>
                                        <span class="text-xs text-gray-500">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700">{{ $comment->comment }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Add Comment Form -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        {{ __('common.add_comment') }}
                    </h3>

                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif

                    <!-- Comment Form -->
                    <form action="{{ route('public.projects.comment.store', $suggestion->id) }}" method="POST" class="space-y-4">
                        @csrf

                        @guest
                            <!-- Guest User Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="user_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('common.your_name') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           id="user_name"
                                           name="user_name"
                                           value="{{ old('user_name') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('user_name') border-red-500 @enderror"
                                           placeholder="{{ __('common.name_surname_placeholder') }}"
                                           required>
                                    @error('user_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="user_email" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('common.your_email') }}
                                    </label>
                                    <input type="email"
                                           id="user_email"
                                           name="user_email"
                                           value="{{ old('user_email') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('user_email') border-red-500 @enderror"
                                           placeholder="{{ __('common.email_optional_placeholder') }}">
                                    @error('user_email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <!-- Authenticated User Info -->
                            <div class="p-3 bg-blue-50 rounded-md">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="text-sm text-blue-800">
                                        {!! __('common.commenting_as', ['name' => '<strong>' . e(Auth::user()->name) . '</strong>']) !!}
                                    </span>
                                </div>
                            </div>
                        @endguest

                        <!-- Comment Textarea -->
                        <div>
                            <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('common.your_comment') }} <span class="text-red-500">*</span>
                            </label>
                            <textarea id="comment"
                                      name="comment"
                                      rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('comment') border-red-500 @enderror"
                                      placeholder="{{ __('common.comment_placeholder') }}"
                                      required>{{ old('comment') }}</textarea>
                            @error('comment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                {{ __('common.comment_moderation_notice') }}
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-between">
                            <div class="text-xs text-gray-500">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('common.comments_visible_after_approval') }}
                            </div>
                            <button type="submit"
                                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                {{ __('common.submit_comment') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-4">
                    <!-- Suggestion Info -->
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('common.suggestion_information') }}</h3>

                    <div class="space-y-4">
                        <!-- Category -->
                        <div>
                            <span class="text-sm font-medium text-gray-500">{{ __('common.category') }}</span>
                            <p class="text-sm text-gray-900 mt-1">{{ $suggestion->category->name ?? __('common.uncategorized') }}</p>
                        </div>

                        <!-- Creator -->
                        @if($suggestion->createdBy)
                        <div>
                            <span class="text-sm font-medium text-gray-500">{{ __('common.suggestion_creator') }}</span>
                            <p class="text-sm text-gray-900 mt-1">{{ $suggestion->createdBy->name }}</p>
                        </div>
                        @endif

                        @if($suggestion->project)
                            <!-- Related Project -->
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('common.related_project') }}</span>
                                <p class="text-sm text-gray-900 mt-1">
                                    <a href="{{ route('public.projects.show', $suggestion->project->id) }}"
                                       class="text-blue-600 hover:text-blue-800">
                                        {{ $suggestion->project->title }}
                                    </a>
                                </p>
                            </div>
                        @endif

                        <!-- Stats -->
                        <div class="pt-4 border-t border-gray-200">
                            <span class="text-sm font-medium text-gray-500">{{ __('common.statistics') }}</span>
                            <div class="text-sm text-gray-900 mt-2 space-y-1">
                                <p>{{ $suggestion->likes_count }} {{ __('common.likes') }}</p>
                                <p>{{ $suggestion->approvedComments->count() }} {{ __('common.comments') }}</p>
                                <p>{{ __('common.created_time_ago', ['time' => $suggestion->created_at->diffForHumans()]) }}</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-4 border-t border-gray-200 space-y-2">
                            <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                {{ __('common.like_suggestion') }}
                            </button>
                            <button onclick="document.getElementById('comment').focus(); document.getElementById('comment').scrollIntoView({behavior: 'smooth'});"
                                    class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                                {{ __('common.write_your_comment') }}
                            </button>
                            @if($suggestion->project)
                                <a href="{{ route('public.projects.show', $suggestion->project->id) }}"
                                   class="block w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-center">
                                    {{ __('common.view_project') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation and character counter
document.addEventListener('DOMContentLoaded', function() {
    const commentTextarea = document.getElementById('comment');
    const form = commentTextarea?.closest('form');
    const maxCharacters = 1000;
    const charactersLabel = @json(__('common.characters_label'));

    if (commentTextarea) {
        const characterCounter = document.createElement('div');
        characterCounter.className = 'text-xs text-gray-500 mt-1';
        characterCounter.textContent = `0/${maxCharacters} ${charactersLabel}`;
        commentTextarea.parentNode.insertBefore(characterCounter, commentTextarea.nextSibling.nextSibling);

        commentTextarea.addEventListener('input', function() {
            const length = this.value.length;
            characterCounter.textContent = `${length}/${maxCharacters} ${charactersLabel}`;

            if (length > maxCharacters) {
                characterCounter.className = 'text-xs text-red-500 mt-1';
            } else if (length > maxCharacters * 0.9) {
                characterCounter.className = 'text-xs text-yellow-500 mt-1';
            } else {
                characterCounter.className = 'text-xs text-gray-500 mt-1';
            }
        });
    }

    if (form) {
        form.addEventListener('submit', function() {
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;

            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="w-4 h-4 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                {{ __('common.sending') }}
            `;

            setTimeout(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }, 3000);
        });
    }
});
</script>
@endsection
