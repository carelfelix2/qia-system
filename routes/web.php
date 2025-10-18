<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->hasRole('admin')) {
        return redirect('/admin/dashboard');
    } elseif ($user->hasRole('sales')) {
        return redirect('/sales/dashboard');
    } elseif ($user->hasRole('teknisi')) {
        return redirect('/teknisi/dashboard');
    }

    // User doesn't have a role yet - show pending approval page
    return view('pending-approval');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin');
    });

    Route::get('/admin/users', [App\Http\Controllers\UserManagementController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users/{user}/approve', [App\Http\Controllers\UserManagementController::class, 'approve'])->name('admin.users.approve');
    Route::post('/admin/users/{user}/reject', [App\Http\Controllers\UserManagementController::class, 'reject'])->name('admin.users.reject');
    Route::post('/admin/users/{user}/assign-role', [App\Http\Controllers\UserManagementController::class, 'assignRole'])->name('admin.users.assign-role');
    Route::delete('/admin/users/{user}/remove-role', [App\Http\Controllers\UserManagementController::class, 'removeRole'])->name('admin.users.remove-role');
});

Route::middleware(['auth', 'role:sales'])->group(function () {
    Route::get('/sales/dashboard', [App\Http\Controllers\SalesController::class, 'index'])->name('sales.dashboard');
    Route::get('/sales/input-penawaran', [App\Http\Controllers\SalesController::class, 'create'])->name('sales.input-penawaran.create');
    Route::post('/sales/input-penawaran', [App\Http\Controllers\SalesController::class, 'store'])->name('sales.input-penawaran.store');
});

Route::middleware(['auth', 'role:teknisi'])->group(function () {
    Route::get('/teknisi/dashboard', function () {
        return view('teknisi');
    });
});

require __DIR__.'/auth.php';
