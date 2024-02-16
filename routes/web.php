<?php

use App\Http\Controllers\Admin\{AuthController, Profile, AdminRegister};
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
    return redirect()->route('getAdminLogin');
});

Route::get('/admin/login', [AuthController::class, 'getAdminLogin'])->name('getAdminLogin');
Route::post('/admin/login', [AuthController::class, 'postAdminLogin'])->name('postAdminLogin');

Route::group(['middleware' => ['admin_auth']], function () {

    Route::get('/admin/dashboard/{id}', [Profile::class, 'dashboard'])->name('dashboard');
    Route::get('/admin/logout', [Profile::class, 'logout'])->name('logout');
});


Route::get('/admin/register', [AdminRegister::class, 'showRegistrationForm'])->name('getRegister');
Route::post('/admin/register', [AdminRegister::class, 'postRegister'])->name('postRegister');



Route::get('/{any}', function () {
    return view('admin.layouts.app');
})->where('any', '.*');
