<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PendingRegistration;
use App\Models\Photographer;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $pendingRegistrations = PendingRegistration::count();
        $totalPhotographers = Photographer::count();

        return view('admin.index', compact(
            'totalUsers',
            'pendingRegistrations',
            'totalPhotographers'
        ));
    }
}
