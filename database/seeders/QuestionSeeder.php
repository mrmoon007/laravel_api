<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Question::insert([
            [
                'exam_id' => 1,
                'question_text' => 'What is the capital of France?',
                'type' => 'mcq',
                'correct_answer' => 'Paris',
            ],
            [
                'exam_id' => 1,
                'question_text' => 'What is 2 + 2?',
                'type' => 'mcq',
                'correct_answer' => '4',
            ],
            [
                'exam_id' => 1,
                'question_text' => 'Name a programming language that starts with "P".',
                'type' => 'text',
                'correct_answer' => 'PHP',
            ]
        ]);
    }
}
