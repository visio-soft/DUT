@php
    $decisionType = $project->decision_type;
    $selectedSuggestion = $project->selectedSuggestion;
    $rationale = $project->decision_rationale;
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
                    Karar Verildi
                </h2>
            </div>

            <div style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(10px); padding: 1.5rem; border-radius: 1rem; border: 1px solid rgba(34, 197, 94, 0.2); max-width: 800px; margin: 0 auto;">
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <span style="font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700; color: #166534; background: #dcfce7; padding: 0.25rem 0.75rem; border-radius: 9999px;">
                        {{ $decisionType?->getLabel() ?? 'Sonuçlandı' }}
                    </span>
                </div>

                @if($selectedSuggestion)
                    <div style="background: white; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); margin-bottom: 1.5rem; border: 1px solid #e5e7eb;">
                        <div style="padding: 1.5rem;">
                            <h3 style="font-size: 1.5rem; font-weight: 700; color: #111827; margin: 0 0 0.5rem;">
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
                @endif

                @if($rationale)
                    <div style="background: #f8fafc; padding: 1.25rem; border-radius: 0.75rem; border-left: 4px solid #3b82f6;">
                        <h4 style="font-size: 0.875rem; font-weight: 700; color: #334155; margin: 0 0 0.5rem; text-transform: uppercase;">Yönetim Açıklaması</h4>
                        <p style="margin: 0; color: #475569; font-style: italic;">
                            "{{ $rationale }}"
                        </p>
                    </div>
                @endif
                
                <div style="margin-top: 1.5rem; text-align: center;">
             
                </div>
            </div>
        </div>
    </div>
</div>
