<?php

use App\Http\Controllers\AksesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

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

Route::pattern('id', '[0-9]+');

// Authentication Routes
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->group(function () { // middleware auth, artinya hanya bisa diakses oleh user yang sudah login
    Route::get('/dashboard', [WelcomeController::class, 'index']);

    Route::middleware(['authorize:ADM,STF'])->group(function () { // artinya semua route di dalam group ini harus punya role ADM (Administrator)
        Route::group(['prefix' => 'user'], function () {
            Route::get('/', [UserController::class, 'index']);      // menampilkan halaman awal user
            Route::post('/list', [UserController::class, 'list']);        // menampilkan data user dalam bentuk json untuk datatables
            Route::get('/create_ajax', [UserController::class, 'create_ajax']); // Menampilkan halaman form tambah user ajax
            Route::post('/ajax', [UserController::class, 'store_ajax']); // menyimpan data user baru ajax
            Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // menampilkan halaman form edit user Ajax
            Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); // menyimpan perubahan data user Ajax
            Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete user Ajax
            Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Untuk hapus data user Ajax
            Route::get('/import', [UserController::class, 'import']);
            Route::post('/import_ajax', [UserController::class, 'import_ajax']);
        });
    });

    Route::middleware(['authorize:ADM,STF'])->group(function () { // artinya semua route di dalam group ini harus punya role ADM (Administrator)
        Route::group(['prefix' => 'akses'], function () {
            Route::get('/', [AksesController::class, 'index']);      // menampilkan halaman awal user
            Route::post('/list', [AksesController::class, 'list']);        // menampilkan data user dalam bentuk json untuk datatables
            Route::post('/update_akses', [AksesController::class, 'update_akses']);        // menampilkan data user dalam bentuk json untuk datatables
        });
    });
});
