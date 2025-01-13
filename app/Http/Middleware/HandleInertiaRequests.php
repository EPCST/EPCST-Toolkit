<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Native\Laravel\Facades\Settings;

class HandleInertiaRequests extends Middleware
{
  /**
   * The root template that's loaded on the first page visit.
   *
   * @see https://inertiajs.com/server-side-setup#root-template
   *
   * @var string
   */
  protected $rootView = 'app';

  /**
   * Determines the current asset version.
   *
   * @see https://inertiajs.com/asset-versioning
   */
  public function version(Request $request): ?string
  {
    return parent::version($request);
  }

  /**
   * Define the props that are shared by default.
   *
   * @see https://inertiajs.com/shared-data
   *
   * @return array<string, mixed>
   */
  public function share(Request $request): array {
    return array_merge(parent::share($request), [
      'auth.user' => fn () => $request->user()
        ? $request->user()->only('id', 'first_name', 'last_name', 'email', 'token', 'username', 'department', 'role')
        : null,
      'app' => [
        'version' => env('NATIVEPHP_APP_VERSION', '1.0.0'),
        'settings' => [
          'last_push_date' => Settings::get('last_push_date'),
          'last_pull_date' => Settings::get('last_pull_date'),
          'academic_years' => Settings::get('academic_years'),
          'academic_year' => Settings::get('academic_year'),
          'period' => Settings::get('period', 'prelim')
        ]
      ]
    ]);
  }
}
