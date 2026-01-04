<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserConroller;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])->name('home');

    Route::resource('products', ProductController::class);
    Route::get('products-report-sales', [ProductController::class, 'reportSales'])->name('products.report.sales');

    Route::resource('users', UserConroller::class);
    Route::get('employees-invoices-report', [UserConroller::class, 'employeesInvoicesReport'])->name('employees.invoices.report');

    Route::resource('invoices', InvoiceController::class);
    Route::get('invoices/{id}/pdf', [InvoiceController::class, 'downloadPDF'])->name('invoices.pdf');
    Route::get('invoices-report-today', [InvoiceController::class, 'dailyReport'])->name('invoices.report.today');
    Route::get('invoices-report-monthly', [InvoiceController::class, 'monthlyReport'])->name('invoices.report.monthly');
    Route::resource('roles', RoleController::class);

    Route::resource('permissions', PermissionController::class);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

});

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'loginCheck'])->name('login.check');
