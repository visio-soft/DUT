@extends('filament.pages.user-panel.layout')

@section('title', $project->title . ' - Surveys')

@section('content')
@include('filament.pages.user-panel._shared-colors')

<style>
    /* Header Styles (Simplified from project-suggestions) */
    .page-header {
        padding: 3rem 0;
        background: #f8fafc;
        margin-bottom: 2rem;
    }
    .header-content { text-align: center; }
    .project-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--gray-900);
        margin: 0;
        line-height: 1.1;
    }
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--gray-600);
        text-decoration: none;
        margin-bottom: 1rem;
        font-weight: 500;
    }
    .back-link:hover { color: var(--primary-600); }

    /* Survey Card Styles */
    .survey-card {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .survey-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 0.5rem;
    }
    .survey-desc {
        color: var(--gray-600);
        margin-bottom: 1.5rem;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--gray-800);
    }
    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--gray-300);
        border-radius: 0.5rem;
        transition: border-color 0.2s;
    }
    .form-control:focus {
        border-color: var(--primary-500);
        outline: none;
        box-shadow: 0 0 0 3px rgba(var(--primary-500-rgb), 0.1);
    }
    .btn-submit {
        background: var(--primary-600);
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-submit:hover {
        background: var(--primary-700);
    }
    .radio-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .radio-option {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
    }
</style>

<div class="page-header">
    <div class="container mx-auto px-4">
        <div class="header-content">
            <a href="{{ route('user.project.suggestions', $project->id) }}" class="back-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Back to Suggestions
            </a>
            <h1 class="project-title">{{ $project->title }} Surveys</h1>
            <p class="text-gray-600 mt-2">Participate in decision making by answering these surveys.</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 main-content">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @forelse($surveys as $survey)
        <div class="survey-card">
            <form action="{{ route('user.project.surveys.store', ['id' => $project->id, 'surveyId' => $survey->id]) }}" method="POST">
                @csrf
                <div class="mb-6">
                    <h2 class="survey-title">{{ $survey->title }}</h2>
                    @if($survey->description)
                        <p class="survey-desc">{{ $survey->description }}</p>
                    @endif
                </div>

                @foreach($survey->questions as $question)
                    <div class="form-group">
                        <label class="form-label">
                            {{ $question->text }}
                            @if($question->is_required) <span class="text-red-500">*</span> @endif
                        </label>

                        @if($question->type === 'text')
                            <textarea 
                                name="question_{{ $question->id }}" 
                                class="form-control" 
                                rows="3"
                                {{ $question->is_required ? 'required' : '' }}
                            ></textarea>
                        @elseif($question->type === 'multiple_choice')
                            <div class="radio-group">
                                @if(is_array($question->options))
                                    @foreach($question->options as $option)
                                        @php
                                            $optionText = is_array($option) ? ($option['text'] ?? '') : $option;
                                            $letter = chr(65 + $loop->index); // 65 is ASCII for 'A'
                                        @endphp
                                        <label class="radio-option">
                                            <input 
                                                type="radio" 
                                                name="question_{{ $question->id }}" 
                                                value="{{ $optionText }}"
                                                {{ $question->is_required ? 'required' : '' }}
                                                class="w-4 h-4 text-primary-600 focus:ring-primary-500"
                                            >
                                            <span class="text-gray-700 font-medium mr-1">{{ $letter }})</span>
                                            <span class="text-gray-700">{{ $optionText }}</span>
                                        </label>
                                    @endforeach
                                @endif
                            </div>
                        @endif
                        @error("question_{$question->id}")
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <div class="mt-6">
                    <button type="submit" class="btn-submit">Submit Survey</button>
                </div>
            </form>
        </div>
    @empty
        <div class="text-center py-12">
            <p class="text-gray-500 text-xl">No active surveys available for this project at the moment.</p>
        </div>
    @endforelse
</div>

@include('partials.success-modal')

<script>
    // Check for session success message
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showSuccessModal('{{ __('common.thank_you') }}', '{{ session('success') }}');
        @endif
    });
</script>
@endsection
