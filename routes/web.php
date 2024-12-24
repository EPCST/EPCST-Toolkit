<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubjectController;
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
    return inertia('Dashboard');
  })->name('dashboard');

  Route::group(['prefix' => 'subjects'], function(){
    Route::get('/', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/{subject}', [SubjectController::class, 'show'])->name('subjects.show');
  });

  Route::get('/logout', function(Request $request) {
    Auth::logout();

    return redirect()->route('home');
  })->name('logout');
});
