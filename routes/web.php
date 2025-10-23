<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    // Check if user is approved and has a role
    if ($user->status === 'approved') {
        if ($user->hasRole('admin')) {
            return redirect('/admin/dashboard');
        } elseif ($user->hasRole('sales')) {
            return redirect('/sales/dashboard');
        } elseif ($user->hasRole('teknisi')) {
            return redirect('/teknisi/dashboard');
        } elseif ($user->hasRole('inputer_sap')) {
            return redirect('/sap/dashboard');
        }
    }

    // User is pending approval or rejected - show pending approval page
    return view('pending-approval');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        $totalActiveUsers = \App\Models\User::where('status', 'approved')->count();
        return view('admin', compact('totalActiveUsers'));
    });

    Route::get('/admin/users', [App\Http\Controllers\UserManagementController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users/{user}/approve', [App\Http\Controllers\UserManagementController::class, 'approve'])->name('admin.users.approve');
    Route::post('/admin/users/{user}/reject', [App\Http\Controllers\UserManagementController::class, 'reject'])->name('admin.users.reject');
    Route::post('/admin/users/{user}/assign-role', [App\Http\Controllers\UserManagementController::class, 'assignRole'])->name('admin.users.assign-role');
    Route::delete('/admin/users/{user}/remove-role', [App\Http\Controllers\UserManagementController::class, 'removeRole'])->name('admin.users.remove-role');
    Route::post('/admin/users/{user}/approve-email-change', [App\Http\Controllers\UserManagementController::class, 'approveEmailChange'])->name('admin.users.approve-email-change');
    Route::post('/admin/users/{user}/reject-email-change', [App\Http\Controllers\UserManagementController::class, 'rejectEmailChange'])->name('admin.users.reject-email-change');

    // Equipment management routes
    Route::get('/admin/equipment', [App\Http\Controllers\EquipmentController::class, 'index'])->name('admin.equipment.index');
    Route::post('/admin/equipment', [App\Http\Controllers\EquipmentController::class, 'store'])->name('admin.equipment.store');
    Route::get('/admin/equipment/{equipment}/edit', [App\Http\Controllers\EquipmentController::class, 'edit'])->name('admin.equipment.edit');
    Route::put('/admin/equipment/{equipment}', [App\Http\Controllers\EquipmentController::class, 'update'])->name('admin.equipment.update');
    Route::post('/admin/equipment/import', [App\Http\Controllers\EquipmentController::class, 'import'])->name('admin.equipment.import');
});

Route::middleware(['auth', 'role:sales'])->group(function () {
    Route::get('/sales/dashboard', [App\Http\Controllers\SalesController::class, 'index'])->name('sales.dashboard');
    Route::get('/sales/input-penawaran', [App\Http\Controllers\SalesController::class, 'create'])->name('sales.input-penawaran.create');
    Route::post('/sales/input-penawaran', [App\Http\Controllers\SalesController::class, 'store'])->name('sales.input-penawaran.store');
    Route::get('/sales/daftar-penawaran', [App\Http\Controllers\SalesController::class, 'quotations'])->name('sales.daftar-penawaran');
    Route::get('/sales/quotation/{quotation}/edit', [App\Http\Controllers\SalesController::class, 'edit'])->name('sales.quotation.edit');
    Route::put('/sales/quotation/{quotation}', [App\Http\Controllers\SalesController::class, 'update'])->name('sales.quotation.update');
    Route::delete('/sales/quotation/{quotation}', [App\Http\Controllers\SalesController::class, 'destroy'])->name('sales.quotation.destroy');
    Route::post('/sales/quotations/delete-multiple', [App\Http\Controllers\SalesController::class, 'destroyMultiple'])->name('sales.quotations.destroy-multiple');
});

Route::middleware(['auth', 'role:teknisi'])->group(function () {
    Route::get('/teknisi/dashboard', function () {
        return view('teknisi');
    });
});

Route::middleware(['auth', 'role:inputer_sap'])->group(function () {
    Route::get('/sap/dashboard', [App\Http\Controllers\SapController::class, 'index'])->name('sap.dashboard');
    Route::get('/sap/daftar-penawaran', [App\Http\Controllers\SapController::class, 'quotations'])->name('sap.daftar-penawaran');
    Route::get('/sap/log-perubahan', [App\Http\Controllers\SapController::class, 'revisionLog'])->name('sap.log-perubahan');
    Route::post('/sap/quotation/{quotation}/update-sap', [App\Http\Controllers\SapController::class, 'updateSapNumber'])->name('sap.update-sap-number');
});

// Purchase Orders routes - accessible by admin and inputer_sap
Route::middleware(['auth'])->group(function () {
    Route::resource('purchase-orders', App\Http\Controllers\PurchaseOrderController::class)->except(['edit', 'destroy']);
    Route::post('/purchase-orders/{purchaseOrder}/approve', [App\Http\Controllers\PurchaseOrderController::class, 'approve'])->name('purchase-orders.approve');
});

// Profile routes for all authenticated users
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notification routes for all authenticated users
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

    // Equipment search for sales
    Route::get('/api/equipment/search', [App\Http\Controllers\EquipmentController::class, 'search'])->name('equipment.search');
});

require __DIR__.'/auth.php';
