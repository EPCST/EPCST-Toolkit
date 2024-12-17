<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function(Request $request) {
  return Inertia::render('Sample', [
    'users' => User::all()
  ]);
})->name('home');

Route::post('/', function(Request $request) {
  $formFields = $request->validate([
    'email' => 'required|email',
    'name' => 'required',
    'password' => 'required'
  ]);

  User::create($formFields);
})->name('register');

Route::post('/user/{user}', function(User $user) {
  $user->delete();

  return redirect()->route('home');
})->name('userDelete');

Route::get('/login', function(Request $request) {
  return Inertia::render('Login');
})->name('login');
