<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Display list of all users
    public function index()
    {
        $users = User::all(); // or use paginate() for large datasets
        return view('admin.users', compact('users'));
    }

    // Delete a user by id
    public function remove($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User removed successfully!');
    }
}
