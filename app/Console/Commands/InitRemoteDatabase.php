<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PDO;

class InitRemoteDatabase extends Command
{
  protected $signature = 'db:init-registrar';
  protected $description = 'Initialize the registrar SQLite database';

  public function handle()
  {
    $path = storage_path('app/remote.sqlite');

    // Delete existing file if it exists
    if (file_exists($path)) {
      unlink($path);
    }

    // Create new SQLite database
    $pdo = new PDO("sqlite:{$path}");
    $pdo->exec('PRAGMA foreign_keys = ON;');

    $this->info('Remote SQLite database initialized at: ' . $path);

    // Run migrations on the new database
    $this->call('migrate:fresh', [
      '--database' => 'registrar_sqlite',
      '--force' => true
    ]);

    return Command::SUCCESS;
  }
}
