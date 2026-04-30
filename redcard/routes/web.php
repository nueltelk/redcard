<?php

use App\Http\Controllers\User\UnitController;
use App\Http\Controllers\User\BorrowController;
use App\Http\Controllers\User\HistoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UnitController as AdminUnit;
use App\Http\Controllers\Admin\LoanController as AdminLoan;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Loan;
use App\Models\Unit;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
})->name('home');

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index']);
    Route::get('/admin/units', [AdminUnit::class, 'index']);
    Route::post('/admin/units', [AdminUnit::class, 'store']);
    Route::put('/admin/units/{id}', [AdminUnit::class, 'update']);
    Route::delete('/admin/units/{id}', [AdminUnit::class, 'destroy']);
    Route::get('/admin/loans', [AdminLoan::class, 'index']);
    Route::post('/admin/loans/{id}/return', [AdminLoan::class, 'return']);

    Route::get('/admin/users', [AdminUser::class, 'index']);
    Route::post('/admin/users', [AdminUser::class, 'store']);
    Route::put('/admin/users/{id}', [AdminUser::class, 'update']);
    Route::delete('/admin/users/{id}', [AdminUser::class, 'destroy']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/units', [UnitController::class, 'index']);
    Route::post('/borrow', [BorrowController::class, 'store']);
    Route::get('/history', [HistoryController::class, 'index']);
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if (!$user) {
        return redirect('/login');
    }

    if ($user->role === 'admin') {
        return redirect('/admin');
    }

    $borrowedCount = Loan::query()
        ->where('user_id', $user->id)
        ->where('status', 'borrowed')
        ->count();

    $historyCount = Loan::query()
        ->where('user_id', $user->id)
        ->count();

    $totalStock = Unit::query()->sum('stock');

    $inventoryPreview = Unit::query()
        ->orderBy('name')
        ->limit(6)
        ->get();

    return view('user.dashboard', [
        'totalStock' => $totalStock,
        'borrowedCount' => $borrowedCount,
        'historyCount' => $historyCount,
        'inventoryPreview' => $inventoryPreview,
    ]);
})->middleware('auth');
