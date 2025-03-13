<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('auth.login');
// });

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

Route::middleware('auth', 'role:admin')->group(function () {   
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/anggota', [AdminController::class, 'anggota'])->name('admin.anggota');
    Route::get('/admin/absensi', [AdminController::class, 'absensi'])->name('admin.absensi');
    Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admin/tambahAnggota', [AdminController::class, 'tambahAnggota'])->name('admin.tambahAnggota');
    Route::get('/admin/editAnggota/{id}', [AdminController::class, 'editAnggota'])->name('admin.editAnggota');
    Route::put('/admin/updateAnggota/{id}', [AdminController::class, 'update'])->name('admin.updateAnggota');
    Route::delete('/admin/deleteAnggota/{id}', [AdminController::class, 'destroy'])->name('admin.deleteAnggota');
    Route::get('/admin/absenAnggota/{id}', [AdminController::class, 'absenAnggota'])->name('admin.absenAnggota');
    Route::post('/admin/absenAnggota/perizinan/', [AdminController::class, 'buatPerizinan'])->name('admin.buatPerizinan');
    //Hapus data absen
    Route::post('/admin/absenAnggota/hapusAbsensi/', [AdminController::class, 'hapusAbsensi'])->name('admin.hapusAbsensi');

});

Route::middleware('auth', 'role:user')->group(function () {
    Route::get('/absen', [UserController::class, 'index'])->name('user.index');
    Route::post('/absen/datang', [UserController::class, 'absenDatang'])->name('user.absenDatang');
    Route::post('/absen/pulang', [UserController::class, 'absenPulang'])->name('user.absenPulang');
});


require __DIR__.'/auth.php';
