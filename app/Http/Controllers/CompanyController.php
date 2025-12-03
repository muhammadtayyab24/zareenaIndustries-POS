<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    /**
     * Show the form for creating a new company.
     */
    public function create()
    {
        // Only super admin can create companies
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access');
        }

        return view('pages.companies.create');
    }

    /**
     * Store a newly created company and its admin.
     */
    public function store(Request $request)
    {
        // Only super admin can create companies
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            // Company fields
            'company_name' => ['required', 'string', 'max:255'],
            'company_address' => ['nullable', 'string'],
            'company_email' => ['nullable', 'email', 'max:255', Rule::unique('companies', 'email')->where(function ($query) {
                return $query->where('is_deleted', false);
            })],
            'company_ntn' => ['nullable', 'string', 'max:255'],
            'company_strn' => ['nullable', 'string', 'max:255'],
            'company_tel_no' => ['nullable', 'string', 'max:255'],
            'company_mobile_no' => ['nullable', 'string', 'max:255'],
            'company_website' => ['nullable', 'url', 'max:255'],
            
            // Admin user fields
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email', 'max:255'],
            'admin_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Create company
        $company = Company::create([
            'name' => $validated['company_name'],
            'address' => $validated['company_address'] ?? null,
            'email' => $validated['company_email'] ?? null,
            'ntn' => $validated['company_ntn'] ?? null,
            'strn' => $validated['company_strn'] ?? null,
            'tel_no' => $validated['company_tel_no'] ?? null,
            'mobile_no' => $validated['company_mobile_no'] ?? null,
            'website' => $validated['company_website'] ?? null,
            'status' => 1, // Active by default
            'is_deleted' => false,
        ]);

        // Create company admin
        User::create([
            'name' => $validated['admin_name'],
            'email' => $validated['admin_email'],
            'password' => Hash::make($validated['admin_password']),
            'role' => 1, // Company Admin
            'company_id' => $company->id,
            'status' => 1, // Active
            'is_deleted' => false,
        ]);

        return redirect()->route('companies.create')->with('success', 'Company and Admin created successfully!');
    }

    /**
     * Show the form for creating a company admin (for existing company).
     */
    public function createAdmin()
    {
        // Only super admin can create admins
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $companies = Company::where('is_deleted', false)
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

        return view('pages.companies.create-admin', compact('companies'));
    }

    /**
     * Store a newly created company admin.
     */
    public function storeAdmin(Request $request)
    {
        // Only super admin can create admins
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email', 'max:255', Rule::unique('users')->where(function ($query) use ($request) {
                return $query->where('company_id', $request->company_id)
                    ->where('is_deleted', false);
            })],
            'admin_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Create company admin
        User::create([
            'name' => $validated['admin_name'],
            'email' => $validated['admin_email'],
            'password' => Hash::make($validated['admin_password']),
            'role' => 1, // Company Admin
            'company_id' => $validated['company_id'],
            'status' => 1, // Active
            'is_deleted' => false,
        ]);

        return redirect()->route('companies.create-admin')->with('success', 'Company Admin created successfully!');
    }
}
