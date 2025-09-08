<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;

class ConversationSeeder extends Seeder
{
    public function run()
    {
        // Create test users if they don't exist
        $client = User::firstOrCreate(
            ['email' => 'client@example.com'],
            [
                'name' => 'John Client',
                'password' => bcrypt('password'),
                'role' => 'client',
                'location' => 'Colombo',  
            ]
        );

        $photographer = User::firstOrCreate(
            ['email' => 'photographer@example.com'],
            [
                'name' => 'Jane Photographer',
                'password' => bcrypt('password'),
                'role' => 'photographer',
                'location' => 'Kandy', 
            ]
        );

        // Create conversation
        $conversation = Conversation::firstOrCreate(
            [
                'user_id' => $client->id,
                'photographer_id' => $photographer->id
            ],
            [
                'last_message_at' => now()
            ]
        );

        // Create sample messages
        $messages = [
            [
                'conversation_id' => $conversation->id,
                'sender_id' => $client->id,
                'body' => 'Hi! I\'m interested in booking a photo session.',
                'is_read' => true
            ],
            [
                'conversation_id' => $conversation->id,
                'sender_id' => $photographer->id,
                'body' => 'Hello! I\'d be happy to help. What kind of session are you looking for?',
                'is_read' => true
            ],
            [
                'conversation_id' => $conversation->id,
                'sender_id' => $client->id,
                'body' => 'I need some professional headshots for my LinkedIn profile. When would you be available?',
                'is_read' => false
            ]
        ];

        foreach ($messages as $messageData) {
            Message::firstOrCreate(
                [
                    'conversation_id' => $messageData['conversation_id'],
                    'sender_id' => $messageData['sender_id'],
                    'body' => $messageData['body']
                ],
                $messageData
            );
        }

        $this->command->info('Sample conversation and messages created with location!');
    }
}
