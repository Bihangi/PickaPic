<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackupPortfoliosSeeder extends Seeder
{
    public function run()
    {
        DB::table('portfolios')->insert([
            [
                'id' => 1,
                'photographer_id' => 9,
                'file_path' => 'portfolios/BRYTYw09iVwbgtSNmqYSDQM8Nlk1812AhJkDWR2g.png',
                'original_name' => 'wedding.jpg',
                'file_size' => 1891958,
                'mime_type' => 'image/png',
                'title' => 'Wedding',
                'description' => null,
                'is_featured' => 1,
                'created_at' => '2025-09-19 05:06:17',
                'updated_at' => '2025-09-19 05:06:17',
            ],
            [
                'id' => 2,
                'photographer_id' => 9,
                'file_path' => 'portfolios/QnyTIcfWl8ZbCJt077gOrfwTVtILjl3kNNbbfTsx.jpg',
                'original_name' => 'image6.jpg',
                'file_size' => 45008,
                'mime_type' => 'image/jpeg',
                'title' => 'Scenery',
                'description' => null,
                'is_featured' => 0,
                'created_at' => '2025-09-19 05:07:23',
                'updated_at' => '2025-09-19 05:07:23',
            ],
        ]);
    }
}
