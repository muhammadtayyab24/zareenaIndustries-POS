<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseVoucherController;
use App\Http\Controllers\EmployeeAttendanceController;
use App\Http\Controllers\EmployeeAdvanceSalaryController;
use App\Http\Controllers\EmployeeOvertimeController;
use App\Http\Controllers\EmployeeSalaryController;
use App\Http\Controllers\EmployeeReportController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (require authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management Routes (Admin only - checked in controller)
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    // Vendor Management Routes
    Route::resource('vendors', VendorController::class);
    Route::post('/vendors/{vendor}/toggle-status', [VendorController::class, 'toggleStatus'])->name('vendors.toggle-status');
    
    // Customer Management Routes
    Route::resource('customers', CustomerController::class);
    Route::post('/customers/{customer}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('customers.toggle-status');
    
    // Employee Management Routes
    Route::resource('employees', EmployeeController::class);
    Route::post('/employees/{employee}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('employees.toggle-status');
    
    // Product Management Routes
    Route::resource('product-categories', ProductCategoryController::class);
    Route::post('/product-categories/{productCategory}/toggle-status', [ProductCategoryController::class, 'toggleStatus'])->name('product-categories.toggle-status');
    
    Route::resource('product-types', ProductTypeController::class);
    Route::post('/product-types/{productType}/toggle-status', [ProductTypeController::class, 'toggleStatus'])->name('product-types.toggle-status');
    
    Route::resource('products', ProductController::class);
    Route::post('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    
    // Expense Management Routes
    Route::resource('expense-categories', ExpenseCategoryController::class);
    Route::post('/expense-categories/{expenseCategory}/toggle-status', [ExpenseCategoryController::class, 'toggleStatus'])->name('expense-categories.toggle-status');
    
    Route::resource('expense-vouchers', ExpenseVoucherController::class);
    
    // Employee Module Routes (REST APIs + Web Views)
    Route::prefix('employees')->group(function () {
        // Attendance Routes
        Route::resource('attendances', EmployeeAttendanceController::class)->names([
            'index' => 'employees.attendances.index',
            'create' => 'employees.attendances.create',
            'store' => 'employees.attendances.store',
            'show' => 'employees.attendances.show',
            'edit' => 'employees.attendances.edit',
            'update' => 'employees.attendances.update',
            'destroy' => 'employees.attendances.destroy',
        ]);
        
        // Advance Salary Routes
        Route::resource('advance-salaries', EmployeeAdvanceSalaryController::class)->names([
            'index' => 'employees.advance-salaries.index',
            'create' => 'employees.advance-salaries.create',
            'store' => 'employees.advance-salaries.store',
            'show' => 'employees.advance-salaries.show',
            'edit' => 'employees.advance-salaries.edit',
            'update' => 'employees.advance-salaries.update',
            'destroy' => 'employees.advance-salaries.destroy',
        ]);
        
        // Overtime Routes
        Route::resource('overtimes', EmployeeOvertimeController::class)->names([
            'index' => 'employees.overtimes.index',
            'create' => 'employees.overtimes.create',
            'store' => 'employees.overtimes.store',
            'show' => 'employees.overtimes.show',
            'edit' => 'employees.overtimes.edit',
            'update' => 'employees.overtimes.update',
            'destroy' => 'employees.overtimes.destroy',
        ]);
        
        // Salary Routes
        Route::resource('salaries', EmployeeSalaryController::class)->names([
            'index' => 'employees.salaries.index',
            'create' => 'employees.salaries.create',
            'store' => 'employees.salaries.store',
            'show' => 'employees.salaries.show',
            'edit' => 'employees.salaries.edit',
            'update' => 'employees.salaries.update',
            'destroy' => 'employees.salaries.destroy',
        ]);
        Route::get('salaries/breakdown/get', [EmployeeSalaryController::class, 'getBreakdown'])->name('employees.salaries.breakdown');
        
        // Employee Report Routes
        Route::get('reports/employee', [EmployeeReportController::class, 'getEmployeeReport'])->name('employees.reports.employee');
        Route::get('reports/monthly', [EmployeeReportController::class, 'getMonthlyReport'])->name('employees.reports.monthly');
    });
});
