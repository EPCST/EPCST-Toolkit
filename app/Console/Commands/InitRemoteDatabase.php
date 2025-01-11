<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use PDO;

class InitRemoteDatabase extends Command
{
  protected $signature = 'db:init-registrar';
  protected $description = 'Initialize the registrar SQLite database';

  public function handle()
  {
    $this->info('Remote SQLite database initialized at: ' . $path);

    // Run migrations on the new database
    $this->call('migrate:fresh', [
      '--database' => 'registrar_sqlite',
      '--force' => true
    ]);

    return Command::SUCCESS;
  }
}
