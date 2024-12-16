<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function(Request $request) {
  return Inertia::render('Sample');
})->name('home');

Route::get('/login', function(Request $request) {
  return Inertia::render('Login');
})->name('login');
