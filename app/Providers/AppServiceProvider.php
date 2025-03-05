<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
      // Create SQLite databases in /database folder
      $databasePath = database_path();

      // Create remote.sqlite if it doesn't exist
      $remotePath = $databasePath . '/database.sqlite';
      if (!file_exists($remotePath)) {
        file_put_contents($remotePath, '');
        chmod($remotePath, 0755);
      }

      // Create local.sqlite if it doesn't exist
      $localPath = $databasePath . '/registrar.sqlite';
      if (!file_exists($localPath)) {
        file_put_contents($localPath, '');
        chmod($localPath, 0755);
      }
    }
}
