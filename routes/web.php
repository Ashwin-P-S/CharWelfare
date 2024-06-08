<?php

use App\Http\Controllers\BotManController;
use App\Http\Controllers\ClassificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SchemesController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', [HomeController::class, 'index']);
Route::get('/translate/{lang}', [HomeController::class, 'setLanguage'])->name('translate');

Route::middleware('auth')->group(function () {
    Route::get('/schemes', [SchemesController::class, 'index'])->name('schemes.index');
    Route::get('/schemes/add', [SchemesController::class, 'add'])->name('schemes.add');
    Route::get('/scheme/edit/{id}', [SchemesController::class, 'edit'])->name('schemes.modify');
    Route::get('/scheme/delete/{id}', [SchemesController::class, 'delete'])->name('schemes.delete');

    Route::post('/schemes/add', [SchemesController::class, 'register'])->name('schemes.register');
    Route::post('/scheme/update/{id}', [SchemesController::class, 'update'])->name('schemes.update');
    Route::post("/schemes/search", [SchemesController::class, 'search'])->name('schemes.search');
});

Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);
