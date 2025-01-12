<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Native\Laravel\Facades\Settings;

class DatabaseSyncService
{
  protected string $apiEndpoint;
  protected array $tables = [
    'users',
    'students',
    'subjects',
    'activities',
    'attendances',
    'activity_student',
    'attendance_student_subject',
    'student_subject'
  ];

  public function __construct()
  {
    $this->apiEndpoint = 'http://toolkit-sync.test/api/sync';
  }

  public function syncPull()
  {
    // Send data to remote endpoint
    $response = Http::withHeaders([
      'Content-Type' => 'application/json',
    ])->get($this->apiEndpoint, [
      'datetime' => Settings::get('last_pull_date')
    ]);

    $data = json_decode($response->body(), true);

    try {
      DB::beginTransaction();

      foreach ($this->tables as $table) {
        // Process tables in the correct order
        if (empty($data[$table])) continue;

        $records = $data[$table];

        // Build raw SQL for better performance
        $columns = array_keys($records[0]);

        $values = collect($records)->map(function ($record) {
          return '(' . collect($record)
            ->map(fn($value) => is_null($value) ? 'NULL' : DB::getPdo()->quote($value))
            ->join(', ') . ')';
        })->join(', ');

        DB::unprepared("
          INSERT OR REPLACE INTO {$table} (" . implode(', ', $columns) . ")
          VALUES {$values}
        ");

        DB::table($table)->whereNotNull('deleted_at')->delete();
      }

      DB::commit();

      Settings::set('last_pull_date', Carbon::now());
      return response()->json([
        'message' => 'Data has been pulled successfully.'
      ]);
    } catch (Exception $e) {
      DB::rollBack();
      return response()->json([
        'error' => 'Sync failed',
        'message' => $e->getMessage()
      ], 500);
    }
  }

  public function sync()
  {
    try {
      $syncData = [];

      // Collect all unsynced data
      foreach ($this->tables as $table) {
        $records = $this->getUnsyncedRecords($table);

        if(!empty($records)) {
          $syncData[$table] = $records;
        }
      }

      if(empty($syncData)) {
        return ['success' => true, 'message' => 'No records to sync'];
      }

      // Send data to remote endpoint
      $response = Http::withHeaders([
//        'Authorization' => 'Bearer ' . config('services.sync.token'),
        'Content-Type' => 'application/json',
      ])->post($this->apiEndpoint, [
        'data' => $syncData,
        'timestamp' => now()->toIso8601String()
      ]);

      if($response->successful()) {
        // Mark records as synced
        $this->markAsSynced($syncData);
        return ['success' => true, 'message' => 'Sync completed successfully'];
      }

      throw new \Exception('Remote sync failed: ' . $response->body());

    }
    catch (\Exception $e) {
      Log::error('Sync failed: ' . $e->getMessage());
      return ['success' => false, 'message' => 'Sync failed: ' . $e->getMessage()];
    }
  }

  protected function getUnsyncedRecords($tableName): array
  {
    $query = DB::table($tableName);

    if(!is_null(Settings::get('last_sync_datetime'))) {
      $query->where('updated_at', '>=', Carbon::parse(Settings::get('last_sync_datetime')));
    }

    return $query->get()
      ->map(function ($record) use ($tableName) {
        $data = (array) $record;
        if(!in_array($tableName, ['users', 'students'])) {
          $data['user_id'] = auth()->user()->id;
        }

        return $data;
      })
      ->toArray();
  }

  /**
   * @throws \Exception
   */
  protected function markAsSynced($syncData): void
  {
    DB::beginTransaction();

    try {
      foreach ($syncData as $table => $records) {
        DB::table($table)->whereNotNull('deleted_at')->delete();
      }

      DB::commit();

    } catch (\Exception $e) {
      DB::rollBack();
      throw $e;
    }

    Settings::set('last_sync_datetime', Carbon::now());
  }

  protected function getPrimaryKeyForTable($tableName)
  {
    $result = DB::select("SELECT name FROM pragma_table_info('{$tableName}') WHERE pk = 1");
    return !empty($result) ? $result[0]->name : 'id';
  }

  public function getPendingSyncCount()
  {
    $counts = [];
    foreach ($this->tables as $table) {
      $counts[$table] = DB::table($table)
        ->where('updated_at', '>=', Settings::get('last_sync_datetime', 0))
        ->count();
    }
    return $counts;
  }
}
