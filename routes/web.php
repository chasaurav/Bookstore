<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;

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

// Auth routes
Auth::routes();

// Client routes
Route::get('/', function () {
    return view('welcome');
});

// Admin routes
Route::controller(BookController::class)->prefix('books')->group(function () {
    Route::get('/', 'index');

    Route::get('create', 'create');
    Route::get('datatable', 'getBooksForDatatable');
    Route::get('{book}/edit', 'edit');

    Route::post('/{book}', 'update');
    Route::post('/', 'store');

    Route::delete('/{book}', 'destroy');
});
