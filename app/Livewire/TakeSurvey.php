<?php

namespace App\Livewire;

use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyResponse;
use Livewire\Component;

class TakeSurvey extends Component
{
    public $isOpen = false;
    public $surveyId = null;
    public $survey = null;
    public $answers = [];

    protected $listeners = ['openSurveyModal' => 'openModal'];

    public function openModal($surveyId)
    {
        $this->surveyId = $surveyId;
        $this->survey = Survey::with('questions')->find($surveyId);
        $this->answers = [];
        
        // Initialize answers array
        if ($this->survey) {
            foreach ($this->survey->questions as $question) {
                $this->answers[$question->id] = '';
            }
        }
        
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->survey = null;
        $this->answers = [];
    }

    public function submitSurvey()
    {
        // Validate required fields
        if ($this->survey) {
            foreach ($this->survey->questions as $question) {
                if ($question->is_required && empty($this->answers[$question->id])) {
                    $this->addError('answers.' . $question->id, 'Bu alan zorunludur.');
                    return;
                }
            }
        }

        // Create Response
        $response = SurveyResponse::create([
            'survey_id' => $this->survey->id,
            'user_id' => auth()->id(),
        ]);

        // Create Answers
        foreach ($this->answers as $questionId => $answer) {
            if (!empty($answer)) {
                // Ensure answer is a string (in case it's an array)
                $answerText = is_array($answer) ? implode(', ', $answer) : $answer;
                
                SurveyAnswer::create([
                    'survey_response_id' => $response->id,
                    'survey_question_id' => $questionId,
                    'answer_text' => $answerText,
                ]);
            }
        }

        $this->closeModal();
        
        session()->flash('success', 'Anket yanıtlarınız başarıyla kaydedildi!');
        
        return redirect()->route('user.projects');
    }

    public function render()
    {
        return view('livewire.take-survey');
    }
}
