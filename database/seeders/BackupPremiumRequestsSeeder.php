<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackupPremiumRequestsSeeder extends Seeder
{
    public function run()
    {
        DB::table('premium_requests')->insert([
            [
                'id' => 1,
                'photographer_id' => 9,
                'package_type' => 'quarterly',
                'amount_paid' => 6000.00,
                'payment_slip' => 'premium-requests/premium_9_1758767687.pdf',
                'message' => null,
                'status' => 'approved',
                'requested_at' => '2025-09-25 02:34:48',
                'processed_at' => '2025-09-25 04:11:18',
                'expires_at' => '2025-12-25 04:11:18',
                'created_at' => '2025-09-25 02:34:48',
                'updated_at' => '2025-09-25 04:11:18',
            ],
        ]);
    }
}
