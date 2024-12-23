<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login')->name('home');
Route::middleware('guest')->group(function () {
  Route::get('/login', [AuthController::class, 'login'])->name('login');
  Route::post('/login', [AuthController::class, 'auth'])->name('login.auth');
});

Route::middleware('auth')->group(function(){
  Route::get('/dashboard', function(Request $request) {
    return inertia('Sample');
  })->name('dashboard');

  Route::get('/logout', function(Request $request) {
    Auth::logout();

    return redirect()->route('home');
  })->name('logout');
});
