<?php

// use App\Http\Controllers\HomeController;
// use App\Http\Controllers\ProductController;
// use App\Http\Controllers\SalesController;

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;

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
//     return view('welcome');
// });

// Route::get('/', HomeController::class);

// // Route::get('/products', [ProductController::class, 'index']);
// // Route::prefix('category')->group(function () {
// //     Route::get('/food-beverage', [ProductController::class, 'foodBeverage']);
// //     Route::get('/beauty-health', [ProductController::class, 'beautyHealth']);
// //     Route::get('/home-care', [ProductController::class, 'homeCare']);
// //     Route::get('/baby-kid', [ProductController::class, 'babyKid']);
// // });

// Route::get('/user/{id}/name/{name}', [UserController::class, 'profile']);
// Route::get('/sales', [SalesController::class, 'index']);

// Route::get('/level', [LevelController::class, 'index']);

// Route::get('/kategori', [KategoriController::class, 'index']);

// Route::get('/user', [UserController::class, 'index']);
// Route::get('/user/tambah', [UserController::class, 'tambah']);
// Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);
// Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);
// Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']);
// Route::get('/user/hapus/{id}', [UserController::class, 'hapus']);

Route::pattern('id', '[0-9]+'); //ketika ada parameter {id}, maka harus berupa angka

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->group(function () { // middleware auth, artinya hanya bisa diakses oleh user yang sudah login
    Route::get('/', [WelcomeController::class, 'index']);

    Route::middleware(['authorize:ADM'])->group(function () { // artinya semua route di dalam group ini harus punya role ADM (Administrator)
        Route::group(['prefix' => 'level'], function () {
            Route::get('/', [LevelController::class, 'index']);
            Route::post('/list', [LevelController::class, 'list']);
            Route::get('/create', [LevelController::class, 'create']);
            Route::post("/", [LevelController::class, 'store']);
            Route::get('/create_ajax', [LevelController::class, 'create_ajax']);
            Route::post('/ajax', [LevelController::class, 'store_ajax']);
            Route::get('/{id}', [LevelController::class, 'show']);
            Route::get('/{id}/edit', [LevelController::class, 'edit']);
            Route::put("/{id}", [LevelController::class, 'update']);
            Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']);
            Route::get('/{id}/show_ajax', [LevelController::class, 'show_ajax']);
            Route::delete('/{id}', [LevelController::class, 'destroy']);
        });
    });

    Route::middleware(['authorize:ADM'])->group(function () { // artinya semua route di dalam group ini harus punya role ADM (Administrator)
        Route::group(['prefix' => 'user'], function () {
            Route::get('/', [UserController::class, 'index']);             // menampilkan halaman awal user
            Route::post('/list', [UserController::class, 'list']);        // menampilkan data user dalam bentuk json untuk datatables
            Route::get('/create', [UserController::class, 'create']);    // menampilkan halaman form tambah user
            Route::post("/", [UserController::class, 'store']);          // menyimpan data user baru
            Route::get('/create_ajax', [UserController::class, 'create_ajax']); // Menampilkan halaman form tambah user ajax
            Route::post('/ajax', [UserController::class, 'store_ajax']); // menyimpan data user baru ajax
            Route::get('/{id}', [UserController::class, 'show']);        // menampilkan detail user
            Route::get('/{id}/edit', [UserController::class, 'edit']);  // menampilkan halaman form edit user
            Route::put("/{id}", [UserController::class, 'update']);       // menyimpan perubahan data user
            Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // menampilkan halaman form edit user Ajax
            Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); // menyimpan perubahan data user Ajax
            Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete user Ajax
            Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Untuk hapus data user Ajax
            Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']); // untuk menampilkan detail
            Route::delete('/{id}', [UserController::class, 'destroy']);  // menghapus data user
        });
    });

    Route::middleware(['authorize:ADM,MNG'])->group(function () { // artinya semua route di dalam group ini harus punya role ADM (Administrator) dan MNG (Manager)
        Route::group(['prefix' => 'kategori'], function () {
            Route::get('/', [KategoriController::class, 'index']);
            Route::post('/list', [KategoriController::class, 'list']);
            Route::get('/create', [KategoriController::class, 'create']);
            Route::post("/", [KategoriController::class, 'store']);
            Route::get('/create_ajax', [KategoriController::class, 'create_ajax']);
            Route::post('/ajax', [KategoriController::class, 'store_ajax']);
            Route::get('/{id}', [KategoriController::class, 'show']);
            Route::get('/{id}/edit', [KategoriController::class, 'edit']);
            Route::put("/{id}", [KategoriController::class, 'update']);
            Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']);
            Route::get('/{id}/show_ajax', [KategoriController::class, 'show_ajax']);
            Route::delete('/{id}', [KategoriController::class, 'destroy']);
        });
    });

    Route::middleware(['authorize:ADM,MNG,STF'])->group(function () { // artinya semua route di dalam group ini harus punya role ADM (Administrator) dan MNG (Manager) dan STF (Staff)
        Route::group(['prefix' => 'barang'], function () {
            Route::get('/', [BarangController::class, 'index']);
            Route::post('/list', [BarangController::class, 'list']);
            Route::get('/create', [BarangController::class, 'create']);
            Route::post("/", [BarangController::class, 'store']);
            Route::get('/create_ajax', [BarangController::class, 'create_ajax']);
            Route::post('/ajax', [BarangController::class, 'store_ajax']);
            Route::get('/{id}', [BarangController::class, 'show']);
            Route::get('/{id}/edit', [BarangController::class, 'edit']);
            Route::put("/{id}", [BarangController::class, 'update']);
            Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']);
            Route::get('/{id}/show_ajax', [BarangController::class, 'show_ajax']);
            Route::delete('/{id}', [BarangController::class, 'destroy']);
        });
    });

    Route::middleware(['authorize:ADM,MNG'])->group(function () {
        Route::group(['prefix' => 'supplier'], function () {
            Route::get('/', [SupplierController::class, 'index']);
            Route::post('/list', [SupplierController::class, 'list']);
            Route::get('/create', [SupplierController::class, 'create']);
            Route::post("/", [SupplierController::class, 'store']);
            Route::get('/create_ajax', [SupplierController::class, 'create_ajax']);
            Route::post('/ajax', [SupplierController::class, 'store_ajax']);
            Route::get('/{id}', [SupplierController::class, 'show']);
            Route::get('/{id}/edit', [SupplierController::class, 'edit']);
            Route::put("/{id}", [SupplierController::class, 'update']);
            Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']);
            Route::get('/{id}/show_ajax', [SupplierController::class, 'show_ajax']);
            Route::delete('/{id}', [SupplierController::class, 'destroy']);
        });
    });

    // Route::group(['prefix' => 'user'], function () {
    //     Route::get('/', [UserController::class, 'index']);          //menampilkan halaman awal user
    //     Route::post('/list', [UserController::class, 'list']);      //menampilkan data user dalam bentuk json untuk datatables
    //     Route::get('/create', [UserController::class, 'create']);   //menampilkan halaman form tambah user
    //     Route::post('/', [UserController::class, 'store']);         //menyimpan data user baru

    //     Route::get('/create_ajax', [UserController::class, 'create_ajax']);   //menampilkan halaman form tambah user Ajax
    //     Route::post('/ajax', [UserController::class, 'store_ajax']);        //menyimpan data user baru Ajax

    //     Route::get('/{id}', [UserController::class, 'show']);       //menampilkan detail user
    //     Route::get('/{id}/edit', [UserController::class, 'edit']);  //menampilkan halaman form edit user
    //     Route::put('/{id}', [UserController::class, 'update']);     //menyimpan perubahan data user

    //     Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);  //menampilkan halaman form edit user Ajax
    //     Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); //menyimpan perubahan data user Ajax

    //     Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); //menampilkan form confirm delete user Ajax
    //     Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); //mnghapus data user Ajax

    //     Route::delete('/{id}', [UserController::class, 'destroy']); //menghapus data user
    // });

    // // Untuk mengakses semua route didalam group ini harus punya role ADM (Administrator)
    // Route::middleware(['authorize:ADM'])->group(function () {
    //     Route::get('/level', [LevelController::class, 'index']);           // menampilkan halaman awal level
    //     Route::post('/level/list', [LevelController::class, 'list']);       // menampilkan data level dalam bentuk json untuk datatables
    //     Route::get('/level/create', [LevelController::class, 'create']);    // menampilkan halaman form tambah level
    //     Route::post('/level', [LevelController::class, 'store']);          // menyimpan data level baru

    //     Route::get('/level/create_ajax', [LevelController::class, 'create_ajax']);
    //     Route::post('/level/ajax', [LevelController::class, 'store_ajax']);

    //     Route::get('/level/{id}', [LevelController::class, 'show']);        // menampilkan detail level
    //     Route::get('/level/{id}/edit', [LevelController::class, 'edit']);   // menampilkan halaman form edit level
    //     Route::put("/level/{id}", [LevelController::class, 'update']);      // menyimpan perubahan data level

    //     Route::get('/level/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);
    //     Route::put('/level/{id}/update_ajax', [LevelController::class, 'update_ajax']);

    //     Route::get('/level/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);
    //     Route::delete('/level/{id}/delete_ajax', [LevelController::class, 'delete_ajax']);

    //     Route::delete('/level/{id}', [LevelController::class, 'destroy']);  // menghapus data level
    // });

    // Route::group(['prefix' => 'kategori'], function () {
    //     Route::get('/', [KategoriController::class, 'index']);          // menampilkan halaman awal kategori
    //     Route::post('/list', [KategoriController::class, 'list']);      // menampilkan data kategori dalam bentuk json untuk datatables
    //     Route::get('/create', [KategoriController::class, 'create']);   // menampilkan halaman form tambah kategori
    //     Route::post('/', [KategoriController::class, 'store']);         // menyimpan data kategori baru

    //     Route::get('/create_ajax', [KategoriController::class, 'create_ajax']);
    //     Route::post('/ajax', [KategoriController::class, 'store_ajax']);

    //     Route::get('/{id}', [KategoriController::class, 'show']);       // menampilkan detail kategori
    //     Route::get('/{id}/edit', [KategoriController::class, 'edit']);  // menampilkan halaman form edit kategori
    //     Route::put("/{id}", [KategoriController::class, 'update']);     // menyimpan perubahan data kategori

    //     Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']);
    //     Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']);
    //     Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']);
    //     Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']);

    //     Route::delete('/{id}', [KategoriController::class, 'destroy']); // menghapus data kategori
    // });

    // Route::group(['prefix' => 'supplier'], function () {
    //     Route::get('/', [SupplierController::class, 'index']);
    //     Route::post('/list', [SupplierController::class, 'list']);
    //     Route::get('/create', [SupplierController::class, 'create']);
    //     Route::post('/', [SupplierController::class, 'store']);

    //     Route::get('/create_ajax', [SupplierController::class, 'create_ajax']);
    //     Route::post('/ajax', [SupplierController::class, 'store_ajax']);

    //     Route::get('/{id}', [SupplierController::class, 'show']);
    //     Route::get('/{id}/edit', [SupplierController::class, 'edit']);
    //     Route::put("/{id}", [SupplierController::class, 'update']);

    //     Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']);
    //     Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']);
    //     Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']);
    //     Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']);

    //     Route::delete('/{id}', [SupplierController::class, 'destroy']);
    // });

    // // Untuk mengakses semua route didalam group ini harus punya role ADM (Administrator) dan MNG (Manager)
    // Route::middleware(['authorize:ADM,MNG'])->group(function () {
    //     Route::get('/barang', [BarangController::class, 'index']);
    //     Route::post('/barang/list', [BarangController::class, 'list']);
    //     Route::get('/barang/create', [BarangController::class, 'create']);
    //     Route::post('/barang', [BarangController::class, 'store']);

    //     Route::get('/barang/create_ajax', [BarangController::class, 'create_ajax']);
    //     Route::post('/barang/ajax', [BarangController::class, 'store_ajax']);

    //     Route::get('/barang/{id}', [BarangController::class, 'show']);
    //     Route::get('/barang/{id}/edit', [BarangController::class, 'edit']);
    //     Route::put("/barang/{id}", [BarangController::class, 'update']);

    //     Route::get('/barang/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);
    //     Route::put('/barang/{id}/update_ajax', [BarangController::class, 'update_ajax']);
    //     Route::get('/barang/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);
    //     Route::delete('/barang/{id}/delete_ajax', [BarangController::class, 'delete_ajax']);

    //     Route::delete('/barang/{id}', [BarangController::class, 'destroy']);
    // });
});
