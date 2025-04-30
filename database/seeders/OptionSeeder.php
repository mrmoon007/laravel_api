<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Option::insert([
            ['question_id' => 1, 'option_text' => 'Berlin'],
            ['question_id' => 1, 'option_text' => 'Madrid'],
            ['question_id' => 1, 'option_text' => 'Paris'],
            ['question_id' => 1, 'option_text' => 'Rome'],

            ['question_id' => 2, 'option_text' => '3'],
            ['question_id' => 2, 'option_text' => '4'],
            ['question_id' => 2, 'option_text' => '5'],
            ['question_id' => 2, 'option_text' => '6'],
        ]);
    }
}
