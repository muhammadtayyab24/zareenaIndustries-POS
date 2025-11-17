<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the expense categories.
     */
    public function index()
    {
        $categories = ExpenseCategory::where('is_deleted', false)->orderBy('created_at', 'desc')->get();
        return view('pages.expense-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new expense category.
     */
    public function create()
    {
        return view('pages.expense-categories.create');
    }

    /**
     * Store a newly created expense category in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'status' => ['sometimes', 'integer', 'in:0,1'],
            ]);

            ExpenseCategory::create([
                'name' => $validated['name'],
                'status' => $validated['status'] ?? 1, // Default to active if not provided
                'is_deleted' => false,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Expense Category created successfully.'
                ]);
            }

            return redirect()->route('expense-categories.index')->with('success', 'Expense Category created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }
    }

    /**
     * Show the form for editing the specified expense category.
     */
    public function edit(ExpenseCategory $expenseCategory)
    {
        if ($expenseCategory->is_deleted) {
            abort(404);
        }
        return view('pages.expense-categories.edit', compact('expenseCategory'));
    }

    /**
     * Update the specified expense category in storage.
     */
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        if ($expenseCategory->is_deleted) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Expense Category not found'
                ], 404);
            }
            abort(404);
        }

        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'status' => ['required', 'integer', 'in:0,1'],
            ]);

            $expenseCategory->update($validated);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Expense Category updated successfully.'
                ]);
            }

            return redirect()->route('expense-categories.index')->with('success', 'Expense Category updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }
    }

    /**
     * Remove the specified expense category from storage (soft delete).
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        // Soft delete - modify name to allow reuse
        $expenseCategory->name = $expenseCategory->name . '_deleted_' . time();
        $expenseCategory->is_deleted = true;
        $expenseCategory->save();

        return redirect()->route('expense-categories.index')->with('success', 'Expense Category deleted successfully.');
    }

    /**
     * Toggle expense category status (active/inactive).
     */
    public function toggleStatus(Request $request, ExpenseCategory $expenseCategory)
    {
        if ($expenseCategory->is_deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Expense Category not found'
            ], 404);
        }

        $expenseCategory->status = $expenseCategory->status == 1 ? 0 : 1;
        $expenseCategory->save();

        return response()->json([
            'success' => true,
            'status' => $expenseCategory->status,
            'message' => $expenseCategory->status == 1 ? 'Expense Category activated successfully.' : 'Expense Category deactivated successfully.'
        ]);
    }
}
