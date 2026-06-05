<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Business\BusinessController;
use App\Http\Controllers\Admin\AdminController;

//Frontend Controller
Route::get('/', [FrontendController::class, 'index'])->name('index');

//Auth Controller
Route::group(['middleware'=>['authCheck']], function(){
    Route::any('/login',[AuthController::class,'login'])->name('login');
    Route::any('/forgot-password',[AuthController::class,'forgotPassword'])->name('forgotPassword');
    Route::get('/reset-password/{token}',[AuthController::class,'resetPassword'])->name('resetPassword');
    Route::post('/reset-password-check',[AuthController::class,'resetPasswordCheck'])->name('resetPasswordCheck');
    Route::any('/register',[AuthController::class,'register'])->name('register');
    Route::post('/log-out',[AuthController::class,'logout'])->name('logout');
});


//Customer Route Group Start
Route::group(['prefix'=>'customer', 'as'=>'customer.','middleware'=>['auth','role:customer']], function(){
    Route::get('/dashboard',[CustomerController::class,'dashboard'])->name('dashboard');
});

//Business Route Group Start
Route::group(['prefix'=>'supplier', 'as'=>'business.','middleware'=>['auth','role:business']], function(){
    Route::get('/dashboard',[BusinessController::class,'dashboard'])->name('dashboard');
});

//Admin Route Group Start
Route::group(['prefix'=>'admin', 'as'=>'admin.','middleware'=>['auth','role:admin']], function(){
    Route::get('/dashboard',[AdminController::class,'dashboard'])->name('dashboard');
});