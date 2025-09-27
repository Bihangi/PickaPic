<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackupBookingsSeeder extends Seeder
{
    public function run()
    {
        DB::table('bookings')->insert([
            [
                'id' => 1,
                'created_at' => null,
                'updated_at' => '2025-09-20 09:53:52',
                'user_id' => 1,
                'photographer_id' => 14,
                'package_id' => 1,
                'event_date' => '2025-09-10',
                'event_details' => null,
                'contact_number' => '0721344567',
                'custom_hours' => null,
                'special_requirements' => null,
                'total_price' => 60000.00,
                'status' => 'cancelled',
            ],
            [
                'id' => 2,
                'created_at' => null,
                'updated_at' => '2025-09-08 13:57:11',
                'user_id' => 18,
                'photographer_id' => 12,
                'package_id' => 2,
                'event_date' => '2025-09-04',
                'event_details' => null,
                'contact_number' => '0715668875',
                'custom_hours' => null,
                'special_requirements' => null,
                'total_price' => 50000.00,
                'status' => 'completed',
            ],
            [
                'id' => 3,
                'created_at' => '2025-09-19 16:26:24',
                'updated_at' => '2025-09-23 15:56:31',
                'user_id' => 1,
                'photographer_id' => 9,
                'package_id' => 3,
                'event_date' => '2025-09-22',
                'event_details' => 'Need you to cover an outdoor engagement party',
                'contact_number' => '0721234567',
                'custom_hours' => 0,
                'special_requirements' => null,
                'total_price' => 65000.00,
                'status' => 'declined',
            ],
            [
                'id' => 4,
                'created_at' => '2025-09-23 03:38:10',
                'updated_at' => '2025-09-23 15:56:48',
                'user_id' => 1,
                'photographer_id' => 9,
                'package_id' => 3,
                'event_date' => '2025-09-23',
                'event_details' => 'Wedding',
                'contact_number' => '0721234567',
                'custom_hours' => 0,
                'special_requirements' => null,
                'total_price' => 65000.00,
                'status' => 'confirmed',
            ],
            [
                'id' => 5,
                'created_at' => '2025-09-25 05:32:35',
                'updated_at' => '2025-09-25 05:44:34',
                'user_id' => 11,
                'photographer_id' => 9,
                'package_id' => 3,
                'event_date' => '2025-09-30',
                'event_details' => 'Outdoor Birthday Party Photoshoot',
                'contact_number' => '0721234567',
                'custom_hours' => 0,
                'special_requirements' => null,
                'total_price' => 65000.00,
                'status' => 'declined',
            ],
            [
                'id' => 7,
                'created_at' => '2025-09-25 05:46:06',
                'updated_at' => '2025-09-25 05:46:06',
                'user_id' => 11,
                'photographer_id' => 9,
                'package_id' => 3,
                'event_date' => '2025-09-29',
                'event_details' => 'Engagement',
                'contact_number' => '0776655443',
                'custom_hours' => 0,
                'special_requirements' => null,
                'total_price' => 65000.00,
                'status' => 'pending',
            ],
        ]);
    }
}
