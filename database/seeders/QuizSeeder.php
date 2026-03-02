<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | QUIZ 1 — Tech & Programming
        |--------------------------------------------------------------------------
        */

        $quiz1 = Quiz::create([
            'title' => 'Tech & Programming Quiz',
            'description' => 'Test your programming knowledge.',
        ]);

        $techQuestions = [

            [
                'question' => 'What does CPU stand for?',
                'answers' => [
                    ['Central Processing Unit', true],
                    ['Computer Personal Unit', false],
                    ['Central Power Utility', false],
                    ['Control Processing User', false],
                ]
            ],
            [
                'question' => 'Which language runs in the browser?',
                'answers' => [
                    ['JavaScript', true],
                    ['Python', false],
                    ['C++', false],
                    ['Java', false],
                ]
            ],
            [
                'question' => 'What does HTML stand for?',
                'answers' => [
                    ['HyperText Markup Language', true],
                    ['HighText Machine Language', false],
                    ['Hyper Tool Markup Language', false],
                    ['Home Text Mark Language', false],
                ]
            ],
            [
                'question' => 'What year was JavaScript created?',
                'answers' => [
                    ['1995', true],
                    ['2000', false],
                    ['1989', false],
                    ['1999', false],
                ]
            ],
            [
                'question' => 'Which company created Laravel?',
                'answers' => [
                    ['Taylor Otwell', true],
                    ['Google', false],
                    ['Microsoft', false],
                    ['Facebook', false],
                ]
            ],
            [
                'question' => 'Which database does Laravel commonly use?',
                'answers' => [
                    ['MySQL', true],
                    ['MongoDB', false],
                    ['Firebase', false],
                    ['Cassandra', false],
                ]
            ],
            [
                'question' => 'Which HTTP method is used to create data?',
                'answers' => [
                    ['POST', true],
                    ['GET', false],
                    ['DELETE', false],
                    ['PATCH', false],
                ]
            ],
            [
                'question' => 'Which framework are you using right now?',
                'answers' => [
                    ['Laravel', true],
                    ['Django', false],
                    ['Express', false],
                    ['Spring', false],
                ]
            ],
            [
                'question' => 'What does RAM stand for?',
                'answers' => [
                    ['Random Access Memory', true],
                    ['Read Access Memory', false],
                    ['Rapid Action Module', false],
                    ['Run Active Memory', false],
                ]
            ],
            [
                'question' => 'Which command runs Laravel migrations?',
                'answers' => [
                    ['php artisan migrate', true],
                    ['php run migrate', false],
                    ['artisan migrate', false],
                    ['php migrate', false],
                ]
            ],
        ];

        $this->insertQuestions($quiz1, $techQuestions);


        /*
        |--------------------------------------------------------------------------
        | QUIZ 2 — General Knowledge
        |--------------------------------------------------------------------------
        */

        $quiz2 = Quiz::create([
            'title' => 'General Knowledge Quiz',
            'description' => 'Random general knowledge questions.',
        ]);

        $generalQuestions = [

            [
                'question' => 'Which planet is known as the Red Planet?',
                'answers' => [
                    ['Mars', true],
                    ['Venus', false],
                    ['Jupiter', false],
                    ['Mercury', false],
                ]
            ],
            [
                'question' => 'What is the largest ocean on Earth?',
                'answers' => [
                    ['Pacific Ocean', true],
                    ['Atlantic Ocean', false],
                    ['Indian Ocean', false],
                    ['Arctic Ocean', false],
                ]
            ],
            [
                'question' => 'Who wrote Romeo and Juliet?',
                'answers' => [
                    ['William Shakespeare', true],
                    ['Charles Dickens', false],
                    ['Mark Twain', false],
                    ['Jane Austen', false],
                ]
            ],
            [
                'question' => 'What is the capital of France?',
                'answers' => [
                    ['Paris', true],
                    ['Berlin', false],
                    ['Madrid', false],
                    ['Rome', false],
                ]
            ],
            [
                'question' => 'How many continents are there?',
                'answers' => [
                    ['7', true],
                    ['5', false],
                    ['6', false],
                    ['8', false],
                ]
            ],
            [
                'question' => 'What is H2O?',
                'answers' => [
                    ['Water', true],
                    ['Oxygen', false],
                    ['Hydrogen', false],
                    ['Salt', false],
                ]
            ],
            [
                'question' => 'Which animal is known as the King of the Jungle?',
                'answers' => [
                    ['Lion', true],
                    ['Tiger', false],
                    ['Elephant', false],
                    ['Bear', false],
                ]
            ],
            [
                'question' => 'What is the fastest land animal?',
                'answers' => [
                    ['Cheetah', true],
                    ['Lion', false],
                    ['Horse', false],
                    ['Leopard', false],
                ]
            ],
            [
                'question' => 'Which country invented pizza?',
                'answers' => [
                    ['Italy', true],
                    ['France', false],
                    ['USA', false],
                    ['Spain', false],
                ]
            ],
            [
                'question' => 'Which gas do plants absorb?',
                'answers' => [
                    ['Carbon Dioxide', true],
                    ['Oxygen', false],
                    ['Nitrogen', false],
                    ['Helium', false],
                ]
            ],
        ];

        $this->insertQuestions($quiz2, $generalQuestions);
    }

    private function insertQuestions($quiz, $questions)
    {
        foreach ($questions as $q) {

            $question = Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => $q['question'],
            ]);

            foreach ($q['answers'] as $answer) {
                Answer::create([
                    'question_id' => $question->id,
                    'answer_text' => $answer[0],
                    'is_correct' => $answer[1],
                ]);
            }
        }
    }
}
