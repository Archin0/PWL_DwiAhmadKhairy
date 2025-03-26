<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\WelcomeController;

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

Route::get('/', [WelcomeController::class, 'index']);

Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']);          //menampilkan halaman awal user
    Route::post('/list', [UserController::class, 'list']);      //menampilkan data user dalam bentuk json untuk datatables
    Route::get('/create', [UserController::class, 'create']);   //menampilkan halaman form tambah user
    Route::post('/', [UserController::class, 'store']);         //menyimpan data user baru
    Route::get('/{id}', [UserController::class, 'show']);       //menampilkan detail user
    Route::get('/{id}/edit', [UserController::class, 'edit']);  //menampilkan halaman form edit user
    Route::put('/{id}', [UserController::class, 'update']);     //menyimpan perubahan data user
    Route::delete('/{id}', [UserController::class, 'destroy']); //menghapus data user
});

Route::group(['prefix' => 'level'], function () {
    Route::get('/', [LevelController::class, 'index']);           // menampilkan halaman awal level
    Route::post('/list', [LevelController::class, 'list']);       // menampilkan data level dalam bentuk json untuk datatables
    Route::get('/create', [LevelController::class, 'create']);    // menampilkan halaman form tambah level
    Route::post('/', [LevelController::class, 'store']);          // menyimpan data level baru
    Route::get('/{id}', [LevelController::class, 'show']);        // menampilkan detail level
    Route::get('/{id}/edit', [LevelController::class, 'edit']);   // menampilkan halaman form edit level
    Route::put("/{id}", [LevelController::class, 'update']);      // menyimpan perubahan data level
    Route::delete('/{id}', [LevelController::class, 'destroy']);  // menghapus data level
});
