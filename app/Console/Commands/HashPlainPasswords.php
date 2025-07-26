<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class HashPlainPasswords extends Command
{
    protected $signature = 'users:hash-passwords';
    protected $description = 'Hashes plain-text passwords for users if not already hashed';

    public function handle()
    {
        $users = User::all();
        $hashedCount = 0;

        foreach ($users as $user) {
            if (!Hash::needsRehash($user->password)) {
                continue; // Already hashed
            }

            $user->password = Hash::make($user->password);
            $user->save();
            $hashedCount++;
        }

        $this->info("Hashed passwords for {$hashedCount} users.");
    }
}
