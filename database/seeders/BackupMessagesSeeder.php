<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackupMessagesSeeder extends Seeder
{
    public function run()
    {
        DB::table('messages')->insert([
            [
                'id' => 4,
                'conversation_id' => 1,
                'sender_id' => 1,
                'body' => 'Hi',
                'attachment' => null,
                'is_read' => 1,
                'created_at' => '2025-09-06 03:24:38',
                'updated_at' => '2025-09-06 03:55:36',
            ],
            [
                'id' => 5,
                'conversation_id' => 1,
                'sender_id' => 1,
                'body' => 'Are you Available on the 30th of September?',
                'attachment' => null,
                'is_read' => 1,
                'created_at' => '2025-09-06 03:30:48',
                'updated_at' => '2025-09-06 03:55:36',
            ],
            [
                'id' => 6,
                'conversation_id' => 1,
                'sender_id' => 1,
                'body' => 'or on the 31st?',
                'attachment' => null,
                'is_read' => 1,
                'created_at' => '2025-09-06 03:36:38',
                'updated_at' => '2025-09-06 03:55:36',
            ],
            [
                'id' => 7,
                'conversation_id' => 1,
                'sender_id' => 15,
                'body' => 'Hi. yes I am available on the 30th of September.',
                'attachment' => null,
                'is_read' => 1,
                'created_at' => '2025-09-07 21:32:24',
                'updated_at' => '2025-09-07 21:50:07',
            ],
            [
                'id' => 8,
                'conversation_id' => 4,
                'sender_id' => 1,
                'body' => 'Hi. Are you available on the 30th of September?',
                'attachment' => null,
                'is_read' => 0,
                'created_at' => '2025-09-07 16:03:43',
                'updated_at' => '2025-09-07 16:03:43',
            ],
            [
                'id' => 10,
                'conversation_id' => 6,
                'sender_id' => 1,
                'body' => 'Hi',
                'attachment' => null,
                'is_read' => 1,
                'created_at' => '2025-09-08 18:55:36',
                'updated_at' => '2025-09-09 04:19:09',
            ],
            [
                'id' => 12,
                'conversation_id' => 6,
                'sender_id' => 1,
                'body' => 'Hello from Selenium test',
                'attachment' => null,
                'is_read' => 1,
                'created_at' => '2025-09-08 19:12:48',
                'updated_at' => '2025-09-09 04:19:09',
            ],
            [
                'id' => 14,
                'conversation_id' => 6,
                'sender_id' => 1,
                'body' => 'Hello from Selenium test',
                'attachment' => null,
                'is_read' => 1,
                'created_at' => '2025-09-09 04:14:19',
                'updated_at' => '2025-09-09 04:19:09',
            ],
            [
                'id' => 15,
                'conversation_id' => 7,
                'sender_id' => 1,
                'body' => 'hi',
                'attachment' => null,
                'is_read' => 0,
                'created_at' => '2025-09-09 06:52:11',
                'updated_at' => '2025-09-09 06:52:11',
            ],
        ]);
    }
}
