<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use App\Models\SurveyAnswer;
use App\Models\User;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating surveys...');

        // Get existing projects
        $projects = Project::all();
        
        if ($projects->isEmpty()) {
            $this->command->warn('No projects found. Please create projects first.');
            return;
        }

        // Get some users for responses
        $users = User::all();

        // Sample surveys with questions
        $surveyTemplates = [
            [
                'title' => 'Proje Memnuniyet Anketi',
                'description' => 'Bu proje hakkındaki düşüncelerinizi öğrenmek istiyoruz.',
                'questions' => [
                    [
                        'text' => 'Proje hakkındaki genel değerlendirmeniz nedir?',
                        'type' => 'multiple_choice',
                        'options' => [
                            ['text' => 'Çok memnunum'],
                            ['text' => 'Memnunum'],
                            ['text' => 'Kararsızım'],
                            ['text' => 'Memnun değilim'],
                            ['text' => 'Hiç memnun değilim'],
                        ],
                    ],
                    [
                        'text' => 'Projeyi arkadaşlarınıza tavsiye eder misiniz?',
                        'type' => 'multiple_choice',
                        'options' => [
                            ['text' => 'Kesinlikle evet'],
                            ['text' => 'Muhtemelen evet'],
                            ['text' => 'Emin değilim'],
                            ['text' => 'Muhtemelen hayır'],
                        ],
                    ],
                    [
                        'text' => 'Proje hakkında eklemek istediğiniz görüşler nelerdir?',
                        'type' => 'text',
                    ],
                ],
            ],
            [
                'title' => 'Öneri Değerlendirme Anketi',
                'description' => 'Öneriler hakkındaki görüşlerinizi paylaşın.',
                'questions' => [
                    [
                        'text' => 'Önerilerin kalitesini nasıl değerlendirirsiniz?',
                        'type' => 'multiple_choice',
                        'options' => [
                            ['text' => 'Mükemmel'],
                            ['text' => 'İyi'],
                            ['text' => 'Orta'],
                            ['text' => 'Kötü'],
                        ],
                    ],
                    [
                        'text' => 'En beğendiğiniz öneri hangisi ve neden?',
                        'type' => 'text',
                    ],
                    [
                        'text' => 'Öneri sürecine katılmayı düşünür müsünüz?',
                        'type' => 'multiple_choice',
                        'options' => [
                            ['text' => 'Evet, kesinlikle'],
                            ['text' => 'Belki'],
                            ['text' => 'Hayır'],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Hizmet Kalitesi Anketi',
                'description' => 'Sunulan hizmetler hakkında geri bildirim.',
                'questions' => [
                    [
                        'text' => 'Hizmet kalitesini nasıl puanlarsınız?',
                        'type' => 'multiple_choice',
                        'options' => [
                            ['text' => '5 - Mükemmel'],
                            ['text' => '4 - Çok iyi'],
                            ['text' => '3 - Orta'],
                            ['text' => '2 - Zayıf'],
                            ['text' => '1 - Çok kötü'],
                        ],
                    ],
                    [
                        'text' => 'Hangi alanlarda iyileştirme yapılmalı?',
                        'type' => 'multiple_choice',
                        'options' => [
                            ['text' => 'İletişim'],
                            ['text' => 'Hız'],
                            ['text' => 'Kalite'],
                            ['text' => 'Fiyat'],
                        ],
                    ],
                    [
                        'text' => 'Önerilerinizi detaylı olarak yazınız.',
                        'type' => 'text',
                    ],
                ],
            ],
        ];

        $textAnswerSamples = [
            'Bu proje gerçekten çok güzel düşünülmüş.',
            'Daha fazla detay eklenebilir.',
            'Harika bir fikir, destekliyorum!',
            'Bazı eksikler var ama genel olarak iyi.',
            'Kesinlikle uygulanmalı.',
            'Bence öncelikli olarak ele alınmalı.',
            'Çok faydalı bir proje olacak.',
            'İyileştirmeler yapılabilir.',
            'Toplum için büyük katkı sağlayacak.',
            'Bu konuda daha fazla bilgi gerekli.',
        ];

        $surveyCount = 0;
        $responseCount = 0;

        foreach ($projects->take(5) as $projectIndex => $project) {
            // Create 1-2 surveys per project
            $surveysToCreate = rand(1, 2);
            
            for ($s = 0; $s < $surveysToCreate; $s++) {
                $template = $surveyTemplates[($projectIndex + $s) % count($surveyTemplates)];
                
                $survey = Survey::create([
                    'project_id' => $project->id,
                    'title' => $template['title'],
                    'description' => $template['description'],
                    'status' => true,
                ]);
                $surveyCount++;

                // Create questions
                foreach ($template['questions'] as $order => $questionData) {
                    $question = SurveyQuestion::create([
                        'survey_id' => $survey->id,
                        'text' => $questionData['text'],
                        'type' => $questionData['type'],
                        'options' => $questionData['options'] ?? null,
                        'order' => $order,
                        'is_required' => true,
                    ]);
                }

                // Create 10-30 random responses
                $responsesToCreate = rand(10, 30);
                $questions = $survey->questions;

                for ($r = 0; $r < $responsesToCreate; $r++) {
                    $user = $users->isNotEmpty() && rand(0, 1) ? $users->random() : null;
                    
                    $response = SurveyResponse::create([
                        'survey_id' => $survey->id,
                        'user_id' => $user?->id,
                        'ip_address' => fake()->ipv4(),
                    ]);
                    $responseCount++;

                    // Create answers for each question
                    foreach ($questions as $question) {
                        if ($question->type === 'multiple_choice') {
                            $options = $question->options ?? [];
                            if (!empty($options)) {
                                $selectedIndex = rand(0, count($options) - 1);
                                $answerText = chr(65 + $selectedIndex); // A, B, C, D, E
                            } else {
                                $answerText = 'A';
                            }
                        } else {
                            $answerText = $textAnswerSamples[array_rand($textAnswerSamples)];
                        }

                        SurveyAnswer::create([
                            'survey_response_id' => $response->id,
                            'survey_question_id' => $question->id,
                            'answer_text' => $answerText,
                        ]);
                    }
                }

                $this->command->info("Created survey: {$survey->title} for project: {$project->title}");
            }
        }

        $this->command->info("Created {$surveyCount} surveys with {$responseCount} responses.");
    }
}
