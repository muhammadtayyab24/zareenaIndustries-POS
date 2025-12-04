<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // If super admin, show company and user statistics
        if ($user->isSuperAdmin()) {
            $totalCompanies = Company::where('is_deleted', false)->count();
            $totalUsers = User::where('is_deleted', false)
                ->whereNotNull('company_id')
                ->count();
            
            return view('pages.dashboard', compact('totalCompanies', 'totalUsers'));
        }
        
        // For company users, show regular dashboard
        return view('pages.dashboard');
    }
}
