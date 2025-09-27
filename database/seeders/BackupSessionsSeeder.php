<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackupSessionsSeeder extends Seeder
{
    public function run()
    {
        DB::table('sessions')->insert([
            [
                'id' => 'eub8EXMCcoGlAnDVnQraOuE5MfXeFjMhPqlna0ul',
                'user_id' => 11,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36',
                'payload' => 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiMThtc0FRUkRSQWVUYlg4c094TE5uV3lGeXpKdDZneFlEcnNTOWNFUyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1NjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Bob3RvZ3JhcGhlci9wcmVtaXVtP3ZlcmlmaWVkPXRydWUiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozOToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2NoYXQvdW5yZWFkLWNvdW50Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTE7czoyMzoidmVyaWZpZWRfZm9ybV9zdWJtaXR0ZWQiO2I6MTt9',
                'last_activity' => 1758880128,
            ],
            [
                'id' => 'u6QudYShq8SJ6Fj1evI6TAZYpxGS2PDTpfQGFUfm',
                'user_id' => null,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36',
                'payload' => 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRDE3T0VxZnlCbjJvZDZFeFFXYzZ3TVRjbkxJYWJRMkQxSHlDVHd4VyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jbGllbnQvbG9naW4iO319',
                'last_activity' => 1758883196,
            ],
        ]);
    }
}
