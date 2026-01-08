@php
    $decisionType = $project->decision_type;
    $selectedSuggestion = $project->selectedSuggestion;
    $rationale = $project->decision_rationale;
    $isHybrid = $decisionType === \App\Enums\ProjectDecisionEnum::HYBRID;
@endphp

<div class="decision-banner-container" style="margin-bottom: 2rem;">
    <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 2px solid #22c55e; border-radius: 1rem; padding: 2rem; position: relative; overflow: hidden; box-shadow: 0 10px 30px -10px rgba(34, 197, 94, 0.3);">
        <div style="position: absolute; top: -20px; right: -20px; width: 150px; height: 150px; background: rgba(34, 197, 94, 0.1); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -40px; left: -40px; width: 200px; height: 200px; background: rgba(34, 197, 94, 0.1); border-radius: 50%;"></div>
        
        <div style="position: relative; z-index: 10;">
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; gap: 1rem;">
                <div style="background: #22c55e; color: white; padding: 0.5rem; border-radius: 50%; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                    <svg style="width: 2.5rem; height: 2.5rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                    </svg>
                </div>
                <h2 style="font-size: 2rem; font-weight: 800; color: #15803d; margin: 0; letter-spacing: -0.02em;">
                    {{ __('common.decision_made') }}
                </h2>
            </div>

            <div style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(10px); padding: 1.5rem; border-radius: 1rem; border: 1px solid rgba(34, 197, 94, 0.2); max-width: 800px; margin: 0 auto;">
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <span style="font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700; color: #166534; background: #dcfce7; padding: 0.25rem 0.75rem; border-radius: 9999px;">
                        {{ $decisionType?->getLabel() ?? __('common.decision_made') }}
                    </span>
                </div>

                @if($isHybrid)
                    {{-- Hybrid Proposal Display --}}
                    <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 0.75rem; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); margin-bottom: 1.5rem; border: 2px solid #f59e0b;">
                        <div style="padding: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                                <svg style="width: 1.5rem; height: 1.5rem; color: #d97706;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
                                </svg>
                                <span style="font-size: 0.875rem; font-weight: 700; color: #92400e; text-transform: uppercase; letter-spacing: 0.05em;">
                                    {{ __('common.hybrid_proposal') }}
                                </span>
                            </div>
                            <h3 style="font-size: 1.5rem; font-weight: 700; color: #78350f; margin: 0 0 0.5rem;">
                                {{ __('common.new_proposal') }}
                            </h3>
                            @if($rationale)
                                <p style="color: #92400e; font-size: 1.125rem; line-height: 1.6; margin: 0;">
                                    {{ $rationale }}
                                </p>
                            @endif
                        </div>
                    </div>
                @elseif($selectedSuggestion)
                    {{-- Selected Suggestion Display for MOST_VOTED or ADMIN_CHOICE --}}
                    <div style="background: white; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); margin-bottom: 1.5rem; border: 2px solid #22c55e; position: relative;">
                        <div style="position: absolute; top: 1rem; right: 1rem; background: #22c55e; color: white; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 0.25rem;">
                            <svg style="width: 1rem; height: 1rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                            {{ __('common.winner') }}
                        </div>
                        <div style="padding: 1.5rem;">
                            <h3 style="font-size: 1.5rem; font-weight: 700; color: #111827; margin: 0 0 0.5rem; padding-right: 6rem;">
                                {{ $selectedSuggestion->title }}
                            </h3>
                            <p style="color: #4b5563; font-size: 1.125rem; line-height: 1.6;">
                                {{ $selectedSuggestion->description }}
                            </p>
                            @if($selectedSuggestion->getFirstMediaUrl('images'))
                                <div style="margin-top: 1rem; border-radius: 0.5rem; overflow: hidden; height: 300px;">
                                    <img src="{{ $selectedSuggestion->getFirstMediaUrl('images') }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($rationale)
                        <div style="background: #f8fafc; padding: 1.25rem; border-radius: 0.75rem; border-left: 4px solid #3b82f6;">
                            <h4 style="font-size: 0.875rem; font-weight: 700; color: #334155; margin: 0 0 0.5rem; text-transform: uppercase;">{{ __('common.administration_explanation') }}</h4>
                            <p style="margin: 0; color: #475569; font-style: italic;">
                                "{{ $rationale }}"
                            </p>
                        </div>
                    @endif
                @endif
                
                <div style="margin-top: 1.5rem; text-align: center;">
             
                </div>
            </div>
        </div>
    </div>
</div>
