<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetDatabaseConnection
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    if(auth()->check()) {
      $user = auth()->user();

      // Set connection based on user role
      $connection = match ($user->role) {
        'admin' => 'registrar_sqlite',
        'teacher' => 'nativephp'
      };

      // Set the default connection
      Config::set('database.default', $connection);

      // Reconnect with new default
      DB::purge();
      DB::reconnect();
    }

    return $next($request);
  }
}
