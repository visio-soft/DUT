<div>
    {{-- Survey Modal Backdrop --}}
    @if($isOpen)
    <div 
        class="survey-modal-backdrop"
        wire:click="closeModal"
        style="
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        "
    >
        {{-- Survey Modal Container --}}
        <div 
            wire:click.stop
            class="survey-modal-container"
            style="
                background: linear-gradient(145deg, rgba(255,255,255,0.98) 0%, rgba(248,250,252,0.98) 100%);
                border-radius: 1.5rem;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255,255,255,0.1);
                width: 100%;
                max-width: 600px;
                max-height: 90vh;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                animation: modalSlideIn 0.3s ease-out;
            "
        >
            {{-- Modal Header --}}
            <div style="
                background: linear-gradient(135deg, #1ABF6B 0%, #16a559 100%);
                padding: 1.5rem 2rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                border-bottom: 1px solid rgba(255,255,255,0.2);
            ">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="
                        width: 40px;
                        height: 40px;
                        background: rgba(255,255,255,0.2);
                        border-radius: 12px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    ">
                        <svg style="width: 24px; height: 24px; color: white;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 style="color: white; font-size: 1.25rem; font-weight: 700; margin: 0;">
                            {{ $survey?->title ?? __('common.survey_title') }}
                        </h2>
                        <p style="color: rgba(255,255,255,0.8); font-size: 0.875rem; margin: 0;">
                            {{ __('common.please_answer_all_questions') }}
                        </p>
                    </div>
                </div>
                <button 
                    wire:click="closeModal"
                    style="
                        width: 36px;
                        height: 36px;
                        background: rgba(255,255,255,0.15);
                        border: none;
                        border-radius: 10px;
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        transition: all 0.2s;
                    "
                    onmouseover="this.style.background='rgba(255,255,255,0.25)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.15)'"
                >
                    <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div style="
                padding: 1.5rem 2rem;
                overflow-y: auto;
                flex: 1;
            ">
                @if($survey && $survey->questions->count() > 0)
                    <form wire:submit.prevent="submitSurvey">
                        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                            @foreach($survey->questions as $index => $question)
                                <div style="
                                    background: #f8fafc;
                                    border: 1px solid #e2e8f0;
                                    border-radius: 1rem;
                                    padding: 1.25rem;
                                    transition: all 0.2s;
                                "
                                onmouseover="this.style.borderColor='#1ABF6B'; this.style.boxShadow='0 4px 12px rgba(26, 191, 107, 0.1)'"
                                onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"
                                >
                                    <div style="display: flex; align-items: flex-start; gap: 0.75rem; margin-bottom: 1rem;">
                                        <span style="
                                            background: linear-gradient(135deg, #1ABF6B 0%, #16a559 100%);
                                            color: white;
                                            width: 28px;
                                            height: 28px;
                                            border-radius: 8px;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            font-size: 0.875rem;
                                            font-weight: 700;
                                            flex-shrink: 0;
                                        ">{{ $index + 1 }}</span>
                                        <label style="
                                            font-size: 1rem;
                                            font-weight: 600;
                                            color: #1f2937;
                                            line-height: 1.5;
                                        ">
                                            {{ $question->text }}
                                            @if($question->is_required)
                                                <span style="color: #ef4444; margin-left: 4px;">*</span>
                                            @endif
                                        </label>
                                    </div>

                                    @if($question->type === 'multiple_choice')
                                        <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-left: 2.25rem;">
                                            @foreach($question->options ?? [] as $option)
                                                @if(!empty($option['text']))
                                                    <label style="
                                                        display: flex;
                                                        align-items: center;
                                                        gap: 0.75rem;
                                                        padding: 0.75rem 1rem;
                                                        background: white;
                                                        border: 2px solid {{ ($answers[$question->id] ?? '') === $option['text'] ? '#1ABF6B' : '#e2e8f0' }};
                                                        border-radius: 0.75rem;
                                                        cursor: pointer;
                                                        transition: all 0.2s;
                                                    "
                                                    onmouseover="if(!this.querySelector('input').checked) this.style.borderColor='#a8e6cf'"
                                                    onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='#e2e8f0'"
                                                    >
                                                        <input 
                                                            type="radio" 
                                                            name="question_{{ $question->id }}"
                                                            wire:model="answers.{{ $question->id }}"
                                                            value="{{ $option['text'] }}"
                                                            style="
                                                                width: 20px;
                                                                height: 20px;
                                                                accent-color: #1ABF6B;
                                                            "
                                                        >
                                                        <span style="color: #374151; font-size: 0.9375rem;">{{ $option['text'] }}</span>
                                                    </label>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <div style="margin-left: 2.25rem;">
                                            <textarea 
                                                wire:model="answers.{{ $question->id }}"
                                                rows="3"
                                                placeholder="{{ __('common.write_your_answer') }}"
                                                style="
                                                    width: 100%;
                                                    padding: 0.875rem 1rem;
                                                    border: 2px solid #e2e8f0;
                                                    border-radius: 0.75rem;
                                                    font-size: 0.9375rem;
                                                    color: #374151;
                                                    resize: vertical;
                                                    transition: all 0.2s;
                                                    outline: none;
                                                    font-family: inherit;
                                                "
                                                onfocus="this.style.borderColor='#1ABF6B'; this.style.boxShadow='0 0 0 3px rgba(26, 191, 107, 0.1)'"
                                                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"
                                            ></textarea>
                                        </div>
                                    @endif

                                    @error('answers.' . $question->id)
                                        <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem; margin-left: 2.25rem;">
                                            {{ __('common.this_field_required') }}
                                        </p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </form>
                @else
                    <div style="text-align: center; padding: 3rem 1rem;">
                        <svg style="width: 48px; height: 48px; color: #9ca3af; margin: 0 auto 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/>
                        </svg>
                        <p style="color: #6b7280; font-size: 1rem;">{{ __('common.no_questions_in_survey') }}</p>
                    </div>
                @endif
            </div>

            {{-- Modal Footer --}}
            <div style="
                padding: 1.25rem 2rem;
                background: #f8fafc;
                border-top: 1px solid #e2e8f0;
                display: flex;
                justify-content: flex-end;
                gap: 0.75rem;
            ">
                <button 
                    type="button"
                    wire:click="closeModal"
                    style="
                        padding: 0.75rem 1.5rem;
                        background: white;
                        border: 2px solid #e2e8f0;
                        border-radius: 0.75rem;
                        color: #6b7280;
                        font-size: 0.9375rem;
                        font-weight: 600;
                        cursor: pointer;
                        transition: all 0.2s;
                    "
                    onmouseover="this.style.borderColor='#d1d5db'; this.style.color='#374151'"
                    onmouseout="this.style.borderColor='#e2e8f0'; this.style.color='#6b7280'"
                >
                    {{ __('common.cancel') }}
                </button>
                <button 
                    type="button"
                    wire:click="submitSurvey"
                    wire:loading.attr="disabled"
                    style="
                        padding: 0.75rem 2rem;
                        background: linear-gradient(135deg, #1ABF6B 0%, #16a559 100%);
                        border: none;
                        border-radius: 0.75rem;
                        color: white;
                        font-size: 0.9375rem;
                        font-weight: 700;
                        cursor: pointer;
                        transition: all 0.2s;
                        box-shadow: 0 4px 14px rgba(26, 191, 107, 0.4);
                        display: flex;
                        align-items: center;
                        gap: 0.5rem;
                    "
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(26, 191, 107, 0.5)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 14px rgba(26, 191, 107, 0.4)'"
                >
                    <span wire:loading.remove wire:target="submitSurvey">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                        </svg>
                    </span>
                    <span wire:loading wire:target="submitSurvey">
                        <svg style="width: 18px; height: 18px; animation: spin 1s linear infinite;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                        </svg>
                    </span>
                    <span wire:loading.remove wire:target="submitSurvey">{{ __('common.submit') }}</span>
                    <span wire:loading wire:target="submitSurvey">{{ __('common.submitting') }}</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <style>
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-10px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</div>
