<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackupPendingRegistrationsSeeder extends Seeder
{
    public function run()
    {
        DB::table('pending_registrations')->insert([
            [
                'id' => 18,
                'google_id' => null,
                'name' => 'Kasun De Silva',
                'email' => 'kasun.desilva@gmail.com',
                'contact' => '+94 77 456 7890',
                'location' => 'Colombo',
                'password' => '$2y$12$r..JHnhwe1FGzUJJ3k9PqO83Aedic5SXXjsWhFxdpCT7kP5BZTk3i',
                'role' => 'photographer',
                'google_profile_data' => null,
                'is_google_registered' => 0,
                'created_at' => '2025-08-14 05:13:55',
                'updated_at' => '2025-08-14 05:13:55',
            ],
            [
                'id' => 21,
                'google_id' => null,
                'name' => 'Chathura Perera',
                'email' => 'chathura.perera@gmail.com',
                'contact' => '0764453388',
                'location' => 'Kandy',
                'password' => '$2y$12$AYTCLBwf03cCjvojGZyPyeFbMusuvdtXF98G2XNxznHChogh4ji.a',
                'role' => 'photographer',
                'google_profile_data' => null,
                'is_google_registered' => 0,
                'created_at' => '2025-09-25 13:33:48',
                'updated_at' => '2025-09-25 13:33:48',
            ],
            [
                'id' => 22,
                'google_id' => null,
                'name' => 'Chamika Dias',
                'email' => 'chamika@gmail.com',
                'contact' => '0724466879',
                'location' => 'Colombo',
                'password' => '$2y$12$/9P4fOs67nsqBytOc88LHuO5p.rrQEBlIXKKnlL5KgGi7PhXcqdUS',
                'role' => 'photographer',
                'google_profile_data' => null,
                'is_google_registered' => 0,
                'created_at' => '2025-09-25 21:29:49',
                'updated_at' => '2025-09-25 21:29:49',
            ],
        ]);
    }
}
