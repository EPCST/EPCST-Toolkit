<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DatabaseSyncService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Native\Laravel\Facades\Notification;
use Native\Laravel\Facades\Settings;

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
        if(Auth::attempt($formFields)) {
          // repopulate if unavailable
          if(empty(Settings::get('academic_years'))) {
            $academicYears = Http::withHeaders([
              'accept' => 'application/json',
              'Authorization' => 'Bearer ' . auth()->user()->api_token,
            ])->get($this->baseUrl . '/schoolyear')
              ->json();

            Settings::set('academic_years', $academicYears);
          }

          if(empty(Settings::get('academic_year'))) {
            $academicYears = (array) Settings::get('academic_years');
            Settings::set('academic_year', $academicYears[count($academicYears) - 1]['id']);
          }

          if(empty(Settings::get('academic_year'))) {
            $academicYears = (array) Settings::get('academic_years');
            Settings::set('academic_year', $academicYears[count($academicYears) - 1]['id']);
          }

          if(empty(Settings::get('period'))) {
            Settings::set('period', 'prelim');
          }

          if(empty(Settings::get('last_push_date'))) {
            Settings::set('last_push_date', Carbon::now());
          }

          $dbs = new DatabaseSyncService();
          if(auth()->user()->role === 'admin') {
            $dbs->syncPull($request);
          }
          else if(auth()->user()->role === 'teacher') {
            $dbs->sync($request);
          }

          return redirect()->route('dashboard');
        }

        return redirect()->route('login')->withErrors([
          'username' => 'Invalid username or password.'
        ]);
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

      if(!in_array($response['user']['role'], [User::ROLE_ADMIN, User::ROLE_TEACHER])) {
        return inertia('Login', ['errors' => [
          'username' => 'Unauthorized role. System is for admin and teacher only!'
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

      if($response['user']['role'] === 'admin') {
        Artisan::call('db:init-registrar');
      }

      Auth::loginUsingId($response['user']['id']);

      $academicYears = Http::withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . auth()->user()->api_token,
      ])->get($this->baseUrl . '/schoolyear')
        ->json();

      Settings::set('academic_years', $academicYears);
      Settings::set('academic_year', $academicYears[count($academicYears) - 1]['id']);
      Settings::set('period', 'prelim');

      return redirect()->route('dashboard');
    } catch (Exception $e) {
      return back()->withErrors([
        'username' => $e->getMessage()
      ]);
    }
  }
}
