<?php

namespace App\Http\Controllers;

use App\Models\ExpenseVoucher;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ExpenseVoucherController extends Controller
{
    /**
     * Display a listing of the expense vouchers.
     */
    public function index()
    {
        $vouchers = ExpenseVoucher::where('is_deleted', false)
            ->with(['category'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('pages.expense-vouchers.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new expense voucher.
     */
    public function create()
    {
        $categories = ExpenseCategory::where('is_deleted', false)->where('status', 1)->orderBy('name')->get();
        return view('pages.expense-vouchers.create', compact('categories'));
    }

    /**
     * Store a newly created expense voucher in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cat_id' => ['required', 'exists:expense_categories,id'],
            'month' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        ExpenseVoucher::create([
            'cat_id' => $validated['cat_id'],
            'month' => $validated['month'],
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? null,
            'is_deleted' => false,
        ]);

        return redirect()->route('expense-vouchers.index')->with('success', 'Expense Voucher created successfully.');
    }

    /**
     * Show the form for editing the specified expense voucher.
     */
    public function edit(ExpenseVoucher $expenseVoucher)
    {
        if ($expenseVoucher->is_deleted) {
            abort(404);
        }
        $categories = ExpenseCategory::where('is_deleted', false)->where('status', 1)->orderBy('name')->get();
        return view('pages.expense-vouchers.edit', compact('expenseVoucher', 'categories'));
    }

    /**
     * Update the specified expense voucher in storage.
     */
    public function update(Request $request, ExpenseVoucher $expenseVoucher)
    {
        if ($expenseVoucher->is_deleted) {
            abort(404);
        }

        $validated = $request->validate([
            'cat_id' => ['required', 'exists:expense_categories,id'],
            'month' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $expenseVoucher->update($validated);

        return redirect()->route('expense-vouchers.index')->with('success', 'Expense Voucher updated successfully.');
    }

    /**
     * Remove the specified expense voucher from storage (soft delete).
     */
    public function destroy(ExpenseVoucher $expenseVoucher)
    {
        // Soft delete
        $expenseVoucher->is_deleted = true;
        $expenseVoucher->save();

        return redirect()->route('expense-vouchers.index')->with('success', 'Expense Voucher deleted successfully.');
    }
}
