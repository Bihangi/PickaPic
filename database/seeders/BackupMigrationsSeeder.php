<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackupMigrationsSeeder extends Seeder
{
    public function run()
    {
        DB::table('migrations')->insert([
            [
                'id' => 24,
                'migration' => '0001_01_01_000000_create_users_table',
                'batch' => 8,
            ],
            [
                'id' => 25,
                'migration' => '0001_01_01_000001_create_cache_table',
                'batch' => 8,
            ],
            [
                'id' => 26,
                'migration' => '0001_01_01_000002_create_jobs_table',
                'batch' => 8,
            ],
            [
                'id' => 27,
                'migration' => '2025_07_20_170342_create_personal_access_tokens_table',
                'batch' => 8,
            ],
            [
                'id' => 28,
                'migration' => '2025_07_20_192345_add_contact_to_users_table',
                'batch' => 8,
            ],
            [
                'id' => 30,
                'migration' => '2025_07_21_100605_create_pending_registrations_table',
                'batch' => 8,
            ],
            [
                'id' => 34,
                'migration' => '2025_08_12_033216_create_photographers_table',
                'batch' => 10,
            ],
            [
                'id' => 35,
                'migration' => '2025_08_14_043013_add_user_id_to_photographers_table',
                'batch' => 11,
            ],
            [
                'id' => 36,
                'migration' => '2025_08_14_043148_add_extra_fields_to_photographers_table',
                'batch' => 12,
            ],
            [
                'id' => 37,
                'migration' => '2025_08_14_045414_add_location_to_pending_registrations_table',
                'batch' => 13,
            ],
            [
                'id' => 38,
                'migration' => '2025_08_15_143150_create_packages_table',
                'batch' => 14,
            ],
            [
                'id' => 39,
                'migration' => '2025_08_15_143151_create_reviews_table',
                'batch' => 14,
            ],
            [
                'id' => 40,
                'migration' => '2025_08_16_044057_add_slug_to_photographers_table',
                'batch' => 999,
            ],
            [
                'id' => 41,
                'migration' => '2025_08_17_042142_remove_unnecessary_columns_from_photographers_table',
                'batch' => 1000,
            ],
            [
                'id' => 43,
                'migration' => '2025_08_18_152123_create_availabilities_table',
                'batch' => 1002,
            ],
            [
                'id' => 44,
                'migration' => '2025_08_18_161625_add_status_to_availabilities_table',
                'batch' => 1003,
            ],
            [
                'id' => 45,
                'migration' => '2025_08_18_194157_drop_availabilities_table',
                'batch' => 1004,
            ],
            [
                'id' => 46,
                'migration' => '2025_08_18_194326_create_availabilities_table',
                'batch' => 1005,
            ],
            [
                'id' => 47,
                'migration' => '2025_08_20_012921_create_portfolios_table',
                'batch' => 1006,
            ],
            [
                'id' => 48,
                'migration' => '2025_08_20_020752_update_portfolios_table_add_social_urls',
                'batch' => 1007,
            ],
            [
                'id' => 51,
                'migration' => '2025_08_30_085802_add_display_name_to_reviews_table',
                'batch' => 1008,
            ],
            [
                'id' => 52,
                'migration' => '2025_08_31_032852_rename_description_to_details_in_packages_table',
                'batch' => 1008,
            ],
            [
                'id' => 53,
                'migration' => '2025_08_31_042221_create_bookings_table',
                'batch' => 1009,
            ],
            [
                'id' => 54,
                'migration' => '2025_08_31_043155_add_booking_to_feilds_to_availabilities_table',
                'batch' => 1009,
            ],
            [
                'id' => 55,
                'migration' => '2025_09_03_054050_update_portfolios_table_2',
                'batch' => 1010,
            ],
            [
                'id' => 56,
                'migration' => '2025_09_03_054636_add_socialmedial_to__photographers_table_',
                'batch' => 1010,
            ],
            [
                'id' => 57,
                'migration' => '2025_09_03_112306_add_missing_columns_to_bookings_table',
                'batch' => 1011,
            ],
            [
                'id' => 58,
                'migration' => '2025_09_03_153414_create_availabilities_table',
                'batch' => 1012,
            ],
            [
                'id' => 59,
                'migration' => '2025_09_05_105100_create_notifications_table',
                'batch' => 1013,
            ],
            [
                'id' => 60,
                'migration' => '2025_09_05_105300_create_conversations_table',
                'batch' => 1014,
            ],
            [
                'id' => 61,
                'migration' => '2025_09_05_105354_create_messages_table',
                'batch' => 1014,
            ],
            [
                'id' => 62,
                'migration' => '2025_09_05_151740_rename_client_id_to_user_id_in_conversations_table',
                'batch' => 1015,
            ],
            [
                'id' => 63,
                'migration' => '2025_09_06_031318_add_is_read_to_messages_table',
                'batch' => 1016,
            ],
            [
                'id' => 64,
                'migration' => '2025_09_08_140453_add_is_visible_to_reviews_table',
                'batch' => 1017,
            ],
            [
                'id' => 65,
                'migration' => '2025_09_19_034243_update_portfolios_table_2',
                'batch' => 1018,
            ],
            [
                'id' => 66,
                'migration' => '2025_09_19_045852_add_foreign_key_to_availabilities_table',
                'batch' => 1019,
            ],
            [
                'id' => 67,
                'migration' => '2025_09_20_045105_update_messages_table_add_is_read_and_attachment',
                'batch' => 1020,
            ],
            [
                'id' => 68,
                'migration' => '2025_09_24_123456_create_premium_requests_table',
                'batch' => 1021,
            ],
            [
                'id' => 69,
                'migration' => '2025_07_20_193404_add_contact_and_role_to_users_table',
                'batch' => 1022,
            ],
        ]);
    }
}
