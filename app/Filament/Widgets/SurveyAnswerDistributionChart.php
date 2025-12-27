<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use Filament\Widgets\ChartWidget;

class SurveyAnswerDistributionChart extends ChartWidget
{
    protected static ?string $heading = null;

    protected static ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 4;

    protected static ?string $maxHeight = '500px';

    public function getHeading(): ?string
    {
        return __('common.survey_analytics');
    }

    public function getDescription(): ?string
    {
        $questionCount = $this->getQuestionCount();
        return __('common.multiple_choice') . ': ' . $questionCount . ' ' . __('common.questions');
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('viewSurveys')
                ->label(__('common.survey'))
                ->icon('heroicon-o-clipboard-document-list')
                ->url(\App\Filament\Resources\SurveyResource::getUrl('index'))
                ->color('gray')
                ->size('sm'),
        ];
    }

    protected function getFilters(): ?array
    {
        // Get projects that have multiple choice questions in their surveys
        $projects = Project::query()
            ->whereHas('surveys.questions', fn ($q) => $q->where('type', 'multiple_choice'))
            ->pluck('title', 'id')
            ->toArray();

        return array_merge(
            ['' => __('common.all_projects')],
            $projects
        );
    }

    protected function getQuestionCount(): int
    {
        $query = SurveyQuestion::where('type', 'multiple_choice')
            ->whereHas('survey', fn ($q) => $q->where('status', true));

        if ($this->filter) {
            $query->whereHas('survey', fn ($q) => $q->where('project_id', $this->filter));
        }

        return $query->count();
    }

    protected function getData(): array
    {
        // Get all multiple choice questions
        $query = SurveyQuestion::where('type', 'multiple_choice')
            ->whereHas('survey', fn ($q) => $q->where('status', true))
            ->with('survey')
            ->orderBy('survey_id')
            ->orderBy('order');

        if ($this->filter) {
            $query->whereHas('survey', fn ($q) => $q->where('project_id', $this->filter));
        }

        $questions = $query->limit(10)->get();

        if ($questions->isEmpty()) {
            return [
                'datasets' => [
                    ['label' => __('common.no_data'), 'data' => [0]],
                ],
                'labels' => [__('common.no_mc_questions_found')],
            ];
        }

        // Collect all answer labels (A, B, C, D, E)
        $maxOptions = 5;
        $optionLabels = ['A', 'B', 'C', 'D', 'E'];
        $colors = [
            'rgba(239, 68, 68, 0.8)',   // Red - A
            'rgba(59, 130, 246, 0.8)',  // Blue - B
            'rgba(16, 185, 129, 0.8)',  // Green - C
            'rgba(245, 158, 11, 0.8)',  // Yellow - D
            'rgba(139, 92, 246, 0.8)',  // Purple - E
        ];

        // Build datasets - one dataset per option (A, B, C, D, E)
        $datasets = [];
        for ($i = 0; $i < $maxOptions; $i++) {
            $letter = $optionLabels[$i];
            $data = [];
            $optionTexts = [];

            foreach ($questions as $question) {
                $options = array_values($question->options ?? []);
                if (isset($options[$i])) {
                    $optionText = $options[$i]['text'] ?? '';
                    $optionTexts[] = $optionText;
                    $count = SurveyAnswer::where('survey_question_id', $question->id)
                        ->where(function ($q) use ($letter, $optionText) {
                            $q->where('answer_text', $letter)
                                ->orWhere('answer_text', strtolower($letter))
                                ->orWhere('answer_text', $optionText);
                        })
                        ->count();
                    $data[] = $count;
                } else {
                    $data[] = 0;
                    $optionTexts[] = '';
                }
            }

            // Only add dataset if it has any non-zero data
            if (array_sum($data) > 0) {
                // Get most common option text for this letter
                $commonText = collect($optionTexts)->filter()->first() ?? '';
                $datasets[] = [
                    'label' => $letter . ') ' . \Str::limit($commonText, 30),
                    'data' => $data,
                    'backgroundColor' => $colors[$i],
                    'borderWidth' => 0,
                ];
            }
        }

        // Question labels (shortened)
        $labels = $questions->map(function ($q, $index) {
            return 'S' . ($index + 1) . ': ' . \Str::limit($q->text, 25);
        })->toArray();

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'mode' => 'index',
                ],
            ],
            'scales' => [
                'x' => [
                    'stacked' => false,
                    'grid' => [
                        'display' => false,
                    ],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'stacked' => false,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                    'grid' => [
                        'color' => 'rgba(128, 128, 128, 0.2)',
                    ],
                ],
            ],
        ];
    }
}
