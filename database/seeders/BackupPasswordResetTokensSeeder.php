<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackupPasswordResetTokensSeeder extends Seeder
{
    public function run()
    {
        DB::table('password_reset_tokens')->insert([
            [
                'email' => 'bihangi@gmail.com',
                'token' => '$2y$12$qjEVS1zwYRY6HIMvKdzsr.cHEGqC.Xi/bZarxza3Wa.VeAaq.Flfa',
                'created_at' => '2025-07-28 03:27:16',
            ],
        ]);
    }
}
