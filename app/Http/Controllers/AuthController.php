<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class AuthController extends Controller {
  private string $baseUrl = 'https://gradebook.epcst.edu.ph/api';
  public function login(Request $request) {
    return Inertia::render('Login');
  }

  public function auth(Request $request) {
    $formFields = $request->validate([
      'username' => 'required',
      'password' => 'required'
    ]);

    try {
      $response = Http::withHeaders([
        'accept' => 'application/json',
      ])->post($this->baseUrl . '/login', $formFields)
        ->throw()
        ->json();

      $userExists = User::where('id', $response['user']['id'])->count() > 0;
      if(!$userExists) {
        User::create();
      }

      return redirect()->route('dashboard', [
        'data' => $response
      ]);
    } catch (Exception $e) {
      return back()->withErrors([
        'username' => $e->getMessage() ?? 'Invalid username or password'
      ]);
    }
  }
}
