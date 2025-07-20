<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PhotographerLoginController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.photographer-login'); 
    }

    // Show dashboard for authenticated photographers
    public function index()
    {
        return view('dashboard'); 
    }
}
