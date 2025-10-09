<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class CheckUserProfileSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('name', 'Pradeep Silva')->first();
        if ($user) {
            echo "User found!\n";
            echo "Profile image: " . ($user->profile_image ?? 'null') . "\n";
            echo "Profile picture: " . ($user->profile_picture ?? 'null') . "\n";
        } else {
            echo "User not found\n";
        }
    }
}