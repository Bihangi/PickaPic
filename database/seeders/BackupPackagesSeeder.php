<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackupPackagesSeeder extends Seeder
{
    public function run()
    {
        DB::table('packages')->insert([
            [
                'id' => 1,
                'photographer_id' => 15,
                'name' => 'Birthday Bliss Package',
                'price' => 45000.00,
                'details' => '4 hours of birthday event coverage
200 professionally edited digital photos
Candid & posed shots with family and friends
Free photo collage design for social media
Delivery within 5 days',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 2,
                'photographer_id' => 15,
                'name' => 'Golden Hour Outdoor Shoot',
                'price' => 60000.00,
                'details' => '4-hour outdoor photo session at 1–2 scenic locations
200 edited high-resolution images
Natural light & golden hour portraits
3 outfit changes allowed
Private online gallery for downloads
Optional photo prints (extra charge)',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 3,
                'photographer_id' => 9,
                'name' => 'Golden Hour Outdoor Shoot',
                'price' => 65000.00,
                'details' => '4-hour outdoor photo session at 1–2 scenic locations
200 edited high-resolution images
Natural light & golden hour portraits
3 outfit changes allowed
Private online gallery for downloads
Optional photo prints (extra charge)',
                'created_at' => null,
                'updated_at' => null,
            ],
        ]);
    }
}
