<?php

namespace App\Filament\Pages\UserPanel;

use App\Helpers\BackgroundImageHelper;
use App\Models\Project;
use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\SurveyAnswer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ProjectSurveys
{
    public function index($id)
    {
        $project = Project::with(['suggestions.createdBy'])->findOrFail($id);

        $surveys = Survey::where('project_id', $id)
            ->where('status', true)
            ->with(['questions' => function($q) {
                $q->orderBy('order');
            }])
            ->get();

        $backgroundData = $this->getBackgroundImageData();

        return view('filament.pages.user-panel.project-surveys', array_merge(
            compact('project', 'surveys'),
            $backgroundData
        ));
    }

    public function store($projectId, $surveyId, Request $request)
    {
        $survey = Survey::with('questions')->findOrFail($surveyId);

        // Validation
        $rules = [];
        foreach ($survey->questions as $question) {
            if ($question->is_required) {
                $rules["question_{$question->id}"] = 'required';
            }
        }
        $request->validate($rules);

        // Create Response
        $response = SurveyResponse::create([
            'survey_id' => $survey->id,
            'user_id' => auth()->id(),
            'ip_address' => $request->ip(),
        ]);

        // Save Answers
        foreach ($survey->questions as $question) {
            $inputName = "question_{$question->id}";
            if ($request->has($inputName)) {
                $rawAnswer = $request->input($inputName);
                
                // If checkbox array, encode to JSON or comma string? Model handles text.
                // For multiple choice, we assume radio or simple select for now. 
                // If checkboxes were implemented, we'd need to handle arrays.
                // Implementation plan said "Multiple Choice", usually implies single selection from many.
                // If it allows multiple answers, we store as JSON/CSV.
                // Current schema 'answer_text' is TEXT.

                $answerText = is_array($rawAnswer) ? json_encode($rawAnswer) : $rawAnswer;

                SurveyAnswer::create([
                    'survey_response_id' => $response->id,
                    'survey_question_id' => $question->id,
                    'answer_text' => $answerText,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Survey submitted successfully!');
    }

    private function getBackgroundImageData(): array
    {
        $hasBackgroundImages = BackgroundImageHelper::hasBackgroundImages();
        $randomBackgroundImage = null;

        if ($hasBackgroundImages) {
            $imageData = BackgroundImageHelper::getRandomBackgroundImage();
            $randomBackgroundImage = $imageData ? $imageData['url'] : null;
        }

        return compact('hasBackgroundImages', 'randomBackgroundImage');
    }
}
