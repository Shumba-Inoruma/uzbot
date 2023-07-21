<?php

use App\Http\Controllers\Controller;
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
Route::get('home',[Controller::class, 'home'])->name('home');
Route::get('paynow',[Controller::class, 'ecocash'])->name('ecocash');
// Route::get('smallwebhook',[Controller::class, 'smallwebhook'])->name('smallwebhook');
Route::get('twillie',[Controller::class, 'twillie'])->name('twillie');
Route::post('webhook',[Controller::class, 'webhook'])->name('webhook')->withoutMiddleware(['csrf']);
