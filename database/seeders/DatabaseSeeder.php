<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            BackupUsersSeeder::class,
            BackupPendingRegistrationsSeeder::class,
            BackupPhotographersSeeder::class,
            BackupPackagesSeeder::class,
            BackupPortfoliosSeeder::class,
            BackupBookingsSeeder::class,
            BackupAvailabilitiesSeeder::class,
            BackupReviewsSeeder::class,
            BackupConversationsSeeder::class,
            BackupMessagesSeeder::class,
            BackupPremiumRequestsSeeder::class,

        ]);
    }
}