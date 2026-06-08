<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PayrollController;

/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'pengelola'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('absensi.index');
    }
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Guest (Login & Register)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Dashboard Redirect (setelah login)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return auth()->user()->role === 'pengelola'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('absensi.index');
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| Rute untuk Pekerja (tanpa middleware custom)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('pekerja')->name('absensi.')->group(function () {
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('index');
    Route::post('/check-in', [AbsensiController::class, 'checkIn'])->name('checkIn');
    Route::post('/check-out', [AbsensiController::class, 'checkOut'])->name('checkOut');
    Route::get('/riwayat', [AbsensiController::class, 'riwayat'])->name('riwayat');
    Route::get('/checkout', [AbsensiController::class, 'showCheckoutForm'])->name('checkoutForm');
});

/*
|--------------------------------------------------------------------------
| Rute untuk Admin (Pengelola) - tanpa middleware custom
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::put('/users/{id}', [AdminController::class, 'update'])->name('users.update');
    Route::get('/users/create', [AdminController::class, 'createUserForm'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/absensi', [AdminController::class, 'allAbsensi'])->name('absensi');
    Route::delete('/admin/absensi/{id}', [AdminController::class, 'deleteAbsensi'])->name('admin.absensi.delete');
    Route::put('/absensi/{id}', [AdminController::class, 'updateAbsensi'])->name('absensi.update');
    Route::get('/rekap', [AdminController::class, 'rekapAbsensi'])->name('rekap');

    // Route Payroll (manual)
    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::post('/payroll', [PayrollController::class, 'store'])->name('payroll.store');
    Route::get('/payroll/{id}/edit', [PayrollController::class, 'edit'])->name('payroll.edit');
    Route::put('/payroll/{id}', [PayrollController::class, 'update'])->name('payroll.update');
    Route::delete('/payroll/{id}', [PayrollController::class, 'destroy'])->name('payroll.destroy');
    Route::get('/payroll/print', [PayrollController::class, 'print'])->name('payroll.print');
    Route::get('/payroll/detail/{id}', [PayrollController::class, 'getDetail'])->name('payroll.detail');
    Route::get('/payroll/slip/{id}', [PayrollController::class, 'adminSlip'])->name('payroll.slip');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/slip-gaji', [PayrollController::class, 'slipGaji'])->name('slip.gaji');
    Route::get('/slip-gaji/cetak/{id}', [PayrollController::class, 'cetakSlip'])->name('slip.cetak');
});