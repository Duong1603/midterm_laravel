<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('food',FoodController::class);
Route::get('{id}/Edit', [FoodController::class, "edit"]);
Route::put('/Update/{id}', [FoodController::class, "update"]);
Route::get('/Delete/{id}', [FoodController::class, "delete"]);
Route::get("/Create", [FoodController::class, "create"]);
Route::post("/Store", [FoodController::class, "store"]);