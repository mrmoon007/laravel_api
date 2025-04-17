<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();

        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Md. Mostafijur Rahman',
                'email' => 'mostafijur@gmail.com',
                'password' => '$2y$12$pUnbgzrO5sEJ/GtgbFJux.sevxBf/AdnOrpg3qsaMgTP7G/lM0uSa', // Password : 123456
            ],
            [
                'id' => 2,
                'name' => 'Blaine Keller',
                'email' => 'user@gmail.com',
                'password' => '$2y$12$pUnbgzrO5sEJ/GtgbFJux.sevxBf/AdnOrpg3qsaMgTP7G/lM0uSa', // Password : 123456
            ],
            [
                'id' => 3,
                'name' => 'Blaine Keller',
                'email' => 'executive@gmail.com',
                'password' => '$2y$12$pUnbgzrO5sEJ/GtgbFJux.sevxBf/AdnOrpg3qsaMgTP7G/lM0uSa', // Password : 123456
            ]
        ]);
    }
}
