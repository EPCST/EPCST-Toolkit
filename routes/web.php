<?php

use App\Http\Controllers\ActivityController;
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
    Route::get('/fetch', [SubjectController::class, 'fetchSubjects'])->name('subjects.fetchSubjects');
    Route::get('/{subject}', [SubjectController::class, 'show'])->name('subjects.show');
    Route::get('/{subject}/activities', [ActivityController::class, 'index'])->name('subjects.activities.index');
    Route::get('/{subject}/activities/create', [ActivityController::class, 'create'])->name('subjects.activities.create');
    Route::post('/{subject}/activities/create', [ActivityController::class, 'store'])->name('subjects.activities.store');
    Route::get('/{subject}/activities/{activity}', [ActivityController::class, 'show'])->name('subjects.activities.show');
    Route::post('/{subject}/activities/{activity}', [ActivityController::class, 'update'])->name('subjects.activities.update');
    Route::get('/{subject}/fetch', [SubjectController::class, 'fetchStudents'])->name('subjects.fetchStudents');
  });

  Route::get('/logout', function(Request $request) {
    Auth::logout();

    return redirect()->route('home');
  })->name('logout');
});
