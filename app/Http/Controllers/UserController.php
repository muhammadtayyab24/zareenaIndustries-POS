<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        // Only admin can access
        if (Auth::user()->role != 1) {
            abort(403, 'Unauthorized access');
        }

        $users = User::where('is_deleted', false)->orderBy('created_at', 'desc')->get();
        return view('pages.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Only admin can create users
        if (Auth::user()->role != 1) {
            abort(403, 'Unauthorized access');
        }

        return view('pages.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Only admin can create users
        if (Auth::user()->role != 1) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->where(function ($query) {
                return $query->where('is_deleted', false);
            })],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'integer', 'in:1,2'], // 1 = admin, 2 = manager
            'status' => ['sometimes', 'integer', 'in:0,1'], // 0 = inactive, 1 = active
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => $validated['status'] ?? 1, // Default to active if not provided
            'is_deleted' => false,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Only admin can edit users
        if (Auth::user()->role != 1) {
            abort(403, 'Unauthorized access');
        }

        // Don't allow editing deleted users
        if ($user->is_deleted) {
            abort(404);
        }

        return view('pages.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Only admin can update users
        if (Auth::user()->role != 1) {
            abort(403, 'Unauthorized access');
        }

        // Don't allow updating deleted users
        if ($user->is_deleted) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)->where(function ($query) {
                return $query->where('is_deleted', false);
            })],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'integer', 'in:1,2'], // 1 = admin, 2 = manager
            'status' => ['required', 'integer', 'in:0,1'], // 0 = inactive, 1 = active
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->status = $validated['status'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage (soft delete).
     */
    public function destroy(User $user)
    {
        // Only admin can delete users
        if (Auth::user()->role != 1) {
            abort(403, 'Unauthorized access');
        }

        // Don't allow deleting yourself
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        // Soft delete - modify email to allow reuse
        $user->email = $user->email . '_deleted_' . time();
        $user->is_deleted = true;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user status (active/inactive).
     */
    public function toggleStatus(Request $request, User $user)
    {
        // Only admin can toggle status
        if (Auth::user()->role != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        // Don't allow toggling deleted users
        if ($user->is_deleted) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();

        return response()->json([
            'success' => true,
            'status' => $user->status,
            'message' => $user->status == 1 ? 'User activated successfully.' : 'User deactivated successfully.'
        ]);
    }
}
