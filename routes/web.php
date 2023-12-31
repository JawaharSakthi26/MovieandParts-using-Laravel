<?php

use App\Http\Controllers\ListController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\task2\FormController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::resource('addmovie',MovieController::class);
Route::resource('listmovie',ListController::class);
Route::resource('form',FormController::class);
