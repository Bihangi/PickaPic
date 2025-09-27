<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackupReviewsSeeder extends Seeder
{
    public function run()
    {
        DB::table('reviews')->insert([
            [
                'id' => 2,
                'photographer_id' => 15,
                'user_id' => 1,
                'display_name' => null,
                'rating' => 5,
                'comment' => 'Ayesha did such a good job! She was super friendly and the photos came out amazing. Really happy with her work.',
                'is_visible' => 1,
                'created_at' => '2025-08-30 16:23:37',
                'updated_at' => '2025-08-30 16:23:37',
            ],
            [
                'id' => 3,
                'photographer_id' => 15,
                'user_id' => 1,
                'display_name' => null,
                'rating' => 4,
                'comment' => 'Ayesha was the photographer for my 18th birthday party, and she did an absolutely incredible job. She captured every single moment beautifully and I couldn\'t be happier with the results and would highly recommend her to anyone looking for a talented and dedicated photographer.',
                'is_visible' => 1,
                'created_at' => '2025-08-30 16:24:33',
                'updated_at' => '2025-09-09 03:57:28',
            ],
            [
                'id' => 4,
                'photographer_id' => 15,
                'user_id' => 1,
                'display_name' => 'Mihin',
                'rating' => 3,
                'comment' => 'she did an absolutely incredible job',
                'is_visible' => 1,
                'created_at' => '2025-09-09 04:59:03',
                'updated_at' => '2025-09-09 05:11:36',
            ],
            [
                'id' => 5,
                'photographer_id' => 9,
                'user_id' => 11,
                'display_name' => 'Anonymous',
                'rating' => 5,
                'comment' => 'Pradeep is an exceptional photographer! He has a keen eye for detail and captures every moment beautifully.',
                'is_visible' => 1,
                'created_at' => '2025-09-25 05:18:11',
                'updated_at' => '2025-09-25 12:26:53',
            ],
        ]);
    }
}
