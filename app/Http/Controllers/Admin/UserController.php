<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Display list of all users in custom order
    public function index()
    {
        $users = User::orderByRaw("
            CASE role
                WHEN 'admin' THEN 1
                WHEN 'client' THEN 2
                WHEN 'photographer' THEN 3
                ELSE 4
            END
        ")->get();

        return view('admin.users', compact('users'));
    }

    // Delete a user by id
    public function remove($id)
    {
        $user = User::findOrFail($id);

        // Prevent removing admin
        if($user->role === 'admin'){
            return redirect()->route('admin.users.index')
                             ->with('success', 'Cannot remove admin user!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'User removed successfully!');
    }
}
