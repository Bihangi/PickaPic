<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackupConversationsSeeder extends Seeder
{
    public function run()
    {
        DB::table('conversations')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'photographer_id' => 15,
                'last_message_at' => '2025-09-06 03:36:38',
                'created_at' => '2025-09-05 16:32:30',
                'updated_at' => '2025-09-06 03:36:38',
            ],
            [
                'id' => 4,
                'user_id' => 1,
                'photographer_id' => 17,
                'last_message_at' => '2025-09-07 16:03:43',
                'created_at' => '2025-09-07 16:03:43',
                'updated_at' => '2025-09-07 16:03:43',
            ],
            [
                'id' => 5,
                'user_id' => 1,
                'photographer_id' => 13,
                'last_message_at' => '2025-09-07 16:08:39',
                'created_at' => '2025-09-07 16:08:39',
                'updated_at' => '2025-09-08 18:57:12',
            ],
            [
                'id' => 6,
                'user_id' => 1,
                'photographer_id' => 11,
                'last_message_at' => '2025-09-09 04:14:19',
                'created_at' => '2025-09-08 18:55:35',
                'updated_at' => '2025-09-09 04:14:19',
            ],
            [
                'id' => 7,
                'user_id' => 1,
                'photographer_id' => 12,
                'last_message_at' => '2025-09-09 06:52:11',
                'created_at' => '2025-09-09 06:52:11',
                'updated_at' => '2025-09-09 06:52:11',
            ],
        ]);
    }
}
