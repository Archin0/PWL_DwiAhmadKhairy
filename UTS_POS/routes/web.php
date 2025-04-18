<?php

use App\Http\Controllers\AksesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SupplierController;
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
    Route::get('/', [WelcomeController::class, 'index']);
    Route::get('/dashboard', [WelcomeController::class, 'index']);

    Route::middleware(['authorize:ADM,STF'])->group(function () { // artinya semua route di dalam group ini harus punya role ADM atau STF
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

    Route::middleware(['authorize:ADM,STF'])->group(function () {
        Route::group(['prefix' => 'akses'], function () {
            Route::get('/', [AksesController::class, 'index']);      // menampilkan halaman awal akses
            Route::post('/list', [AksesController::class, 'list']);        // menampilkan data akses dalam bentuk card
            Route::post('/update_akses', [AksesController::class, 'update_akses']);        // mengupdate hak akses user 
        });
    });

    Route::middleware(['authorize:ADM,STF'])->group(function () {
        Route::group(['prefix' => 'kategori'], function () {
            Route::get('/', [KategoriController::class, 'index']);
            Route::post('/list', [KategoriController::class, 'list']);
            Route::get('/create_ajax', [KategoriController::class, 'create_ajax']);
            Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']);
        });
    });

    Route::middleware(['authorize:ADM,STF'])->group(function () { // artinya semua route di dalam group ini harus punya role ADM atau STF
        Route::group(['prefix' => 'barang'], function () {
            Route::get('/', [BarangController::class, 'index']);
            Route::post('/list', [BarangController::class, 'list']);
            Route::get('/create_ajax', [BarangController::class, 'create_ajax']);
            Route::post('/ajax', [BarangController::class, 'store_ajax']);
            Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']);
            Route::get('/import', [BarangController::class, 'import']); // untuk import data barang
            Route::post('/import_ajax', [BarangController::class, 'import_ajax']); // untuk import data barang ajax
            Route::get('/export_excel', [BarangController::class, 'export_excel']); // untuk export data barang xlsx
        });
    });

    Route::middleware(['authorize:ADM,STF'])->group(function () {
        Route::group(['prefix' => 'laporan'], function () {
            Route::get('/exportpdf_kategori', [KategoriController::class, 'export_pdf']);
            Route::get('/exportpdf_user', [UserController::class, 'export_pdf']);
            Route::get('/exportpdf_barang', [BarangController::class, 'export_pdf']);
        });
    });

    Route::middleware(['authorize:ADM,STF'])->group(function () {
        Route::group(['prefix' => 'supplier'], function () {
            Route::get('/', [SupplierController::class, 'index']);      // menampilkan halaman awal supplier
            Route::post('/list', [SupplierController::class, 'list']);        // menampilkan data supplier dalam bentuk json untuk datatables
            Route::get('/create_ajax', [SupplierController::class, 'create_ajax']); // Menampilkan halaman form tambah supplier ajax
            Route::post('/ajax', [SupplierController::class, 'store_ajax']); // menyimpan data supplier baru ajax
            Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']); // menampilkan halaman form edit supplier Ajax
            Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']); // menyimpan perubahan data supplier Ajax
            Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete supplier Ajax
            Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); // Untuk hapus data supplier Ajax
            Route::get('/import', [SupplierController::class, 'import']);
            Route::post('/import_ajax', [SupplierController::class, 'import_ajax']);
            Route::get('/export_excel', [SupplierController::class, 'export_excel']);
        });
    });
});
