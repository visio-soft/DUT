<div class="space-y-4">
    {{-- Question Info --}}
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('common.question_text') }}</h4>
        <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $record->question?->text ?? '-' }}</p>
    </div>

    {{-- Survey Info --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('common.survey') }}</h4>
            <p class="text-gray-900 dark:text-gray-100">{{ $record->question?->survey?->title ?? '-' }}</p>
        </div>
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('common.project') }}</h4>
            <p class="text-gray-900 dark:text-gray-100">{{ $record->question?->survey?->project?->title ?? '-' }}</p>
        </div>
    </div>

    {{-- Answer --}}
    <div class="bg-primary-50 dark:bg-primary-900/30 rounded-lg p-4 border-l-4 border-primary-500">
        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('common.answer') }}</h4>
        <p class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $record->answer_text }}</p>
    </div>

    {{-- User Info --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('common.user') }}</h4>
            <p class="text-gray-900 dark:text-gray-100">{{ $record->response?->user?->name ?? __('common.anonymous') }}</p>
        </div>
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('common.created_at') }}</h4>
            <p class="text-gray-900 dark:text-gray-100">{{ $record->created_at?->format('d.m.Y H:i') ?? '-' }}</p>
        </div>
    </div>
</div>
