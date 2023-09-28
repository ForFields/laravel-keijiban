<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeijibanController;
use App\Http\Controllers\AuthenticatedSessionController;

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

Route::get('/', function () {
    return redirect('/keijiban');
});

//登録してある値を表示
Route::get('/keijiban', [KeijibanController::class, 'display'])->middleware(['auth', 'verified']);

//入力した値を追加
Route::post('/keijiban', [KeijibanController::class, 'insert'])->middleware(['auth', 'verified']);

//登録してある値を編集
Route::put('/keijiban/{id}', [KeijibanController::class, 'update'])->middleware(['auth', 'verified']);

//登録してある値を削除
Route::delete('/keijiban/{id}', [KeijibanController::class, 'delete'])->middleware(['auth', 'verified']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
