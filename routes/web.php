<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\TestController;
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


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('test', [TestController::class, 'index']) ;
});


Route::get('admin-login-form',[AdminController::class,'login_form'])->name('admin.login.form');
Route::post('admin-login-functionality',[AdminController::class,'login_functionality'])->name('admin.login.functionality');
Route::group(['middleware'=>'admin'],function(){
    Route::get('admin/logout',[AdminController::class,'logout'])->name('admin.logout');
    Route::get('admin/dashboard',[AdminController::class,'dashboard'])->name('admin.dashboard');
});