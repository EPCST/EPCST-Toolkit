<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
      $userExists = User::where('username', $formFields['username'])->count() > 0;
      if($userExists) {
        return Auth::attempt($formFields) ?
          redirect()->route('login')->withErrors([
            'username' => 'Invalid username or password.'
          ]) : redirect()->route('dashboard');
      }

      $response = Http::withHeaders([
        'accept' => 'application/json',
      ])->post($this->baseUrl . '/login', $formFields)
        ->json();

      if(!empty($response['errors'])) {
        return inertia('Login', ['errors' => [
          'username' => $response['errors']['username'][0]
        ]]);
      }

      User::create([
        'id' => $response['user']['id'],
        'role' => $response['user']['role'],
        'first_name' => $response['user']['first_name'],
        'last_name' => $response['user']['last_name'],
        'email' => $response['user']['email'],
        'api_token' => $response['token'],
        'username' => $formFields['username'],
        'password' => $formFields['password']
      ]);

      Auth::loginUsingId($response['user']['id']);

      return redirect()->route('dashboard');
    } catch (Exception $e) {
      return back()->withErrors([
        'username' => $e->getMessage()
      ]);
    }
  }
}
