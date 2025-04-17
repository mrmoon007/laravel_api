<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('messages')->delete();

         // Get two users to simulate messaging between them
         $users = User::take(2)->pluck('id');

         if ($users->count() < 2) {
             $this->command->warn('Not enough users to seed messages. Create at least 2 users.');
             return;
         }
 
         [$senderId, $receiverId] = $users;
 
         // Insert sample messages
         DB::table('messages')->insert([
             [
                 'sender_id' => $senderId,
                 'receiver_id' => $receiverId,
                 'message' => 'Hey! How are you doing?',
                 'is_read' => false,
                 'created_at' => now(),
                 'updated_at' => now(),
             ],
             [
                 'sender_id' => $receiverId,
                 'receiver_id' => $senderId,
                 'message' => 'I am good! What about you?',
                 'is_read' => false,
                 'created_at' => now(),
                 'updated_at' => now(),
             ],
         ]);
    }
}
