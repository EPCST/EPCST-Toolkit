 <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
//  protected $tables = [
//    'activities',
//    'activity_student',
//    'attendance_student_subject',
//    'attendances',
//    'students',
//    'subjects',
//    'users'
//    // Add other tables you want to replicate
//  ];
//
//  public function up()
//  {
//    $pgsql = DB::connection('mariadb');
//
//    foreach ($this->tables as $tableName) {
//      if (!Schema::hasTable($tableName)) {
//        $this->error("Table {$tableName} does not exist in SQLite");
//        continue;
//      }
//
//      // Get SQLite table structure
//      $columns = $this->getTableColumns($tableName);
//      $indexes = $this->getTableIndexes($tableName);
//
//      // Create or update table in PostgreSQL
//      if (!$pgsql->getSchemaBuilder()->hasTable($tableName)) {
//        $pgsql->getSchemaBuilder()->create($tableName, function (Blueprint $table) use ($columns, $indexes, $tableName) {
//          $this->createTableStructure($table, $columns, $indexes, $tableName);
//        });
//      }
//
//      // Add is_synced column for sync tracking
//      if (!Schema::connection('mariadb')->hasColumn($tableName, 'is_synced')) {
//        $pgsql->getSchemaBuilder()->table($tableName, function (Blueprint $table) {
//          $table->boolean('is_synced')->default(false);
//        });
//      }
//    }
//  }
//
//  public function down()
//  {
//    $pgsql = DB::connection('mariadb');
//
//    foreach (array_reverse($this->tables) as $tableName) {
//      $pgsql->getSchemaBuilder()->dropIfExists($tableName);
//    }
//  }
//
//  protected function getTableColumns($tableName)
//  {
//    return DB::select("PRAGMA table_info({$tableName})");
//  }
//
//  protected function getTableIndexes($tableName)
//  {
//    return DB::select("SELECT * FROM sqlite_master WHERE type = 'index' AND tbl_name = '{$tableName}'");
//  }
//
//  protected function createTableStructure(Blueprint $table, $columns, $indexes, $tableName)
//  {
//    foreach ($columns as $column) {
//      // Skip SQLite internal columns
//      if ($column->name === 'sqlite_deleted') continue;
//
//      // Check if column should be nullable
//      $isNullable = ($column->notnull == 0 && !$column->pk);
//
//      // Get default value if exists
//      $defaultValue = $column->dflt_value !== null ? trim($column->dflt_value, "'\"") : null;
//
//      // Map SQLite types to PostgreSQL
//      switch (strtolower($column->type)) {
//        case 'integer':
//          if ($column->pk) {
//            $table->id($column->name);
//          } else {
//            $table->integer($column->name)
//              ->nullable($isNullable)
//              ->default($defaultValue);
//          }
//          break;
//
//        case 'text':
//          $table->text($column->name)
//            ->nullable($isNullable)
//            ->default($defaultValue);
//          break;
//
//        case 'varchar':
//        case 'string':
//          $table->string($column->name)
//            ->nullable($isNullable)
//            ->default($defaultValue);
//          break;
//
//        case 'float':
//        case 'double':
//          $table->float($column->name)
//            ->nullable($isNullable)
//            ->default($defaultValue);
//          break;
//
//        case 'boolean':
//          $table->boolean($column->name)
//            ->nullable($isNullable)
//            ->default($defaultValue);
//          break;
//
//        case 'datetime':
//          $table->timestamp($column->name)
//            ->nullable($isNullable)
//            ->default($defaultValue);
//          break;
//
//        case 'date':
//          $table->date($column->name)
//            ->nullable($isNullable)
//            ->default($defaultValue);
//          break;
//
//        default:
//          $table->string($column->name)
//            ->nullable($isNullable)
//            ->default($defaultValue);
//          break;
//      }
//    }
//
//    // Add indexes with shortened names
//    foreach ($indexes as $index) {
//      if (str_contains($index->sql, 'UNIQUE')) {
//        preg_match('/\((.*?)\)/', $index->sql, $matches);
//        if (isset($matches[1])) {
//          // Clean the column names
//          $indexColumns = array_map(function($column) {
//            return trim(str_replace(['"', ' '], '', $column));
//          }, explode(',', $matches[1]));
//
//          // Create a shorter index name
//          $shortIndexName = substr(md5($tableName . implode('_', $indexColumns)), 0, 24);
//
//          // Create the unique constraint with shorter name
//          $table->unique($indexColumns, $shortIndexName);
//        }
//      }
//    }
//  }
};
