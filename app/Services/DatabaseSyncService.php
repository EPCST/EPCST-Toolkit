<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
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
    $this->apiEndpoint = 'https://toolkit.programinity.io/api/sync';
//    if(app()->environment('production')) {
//      $this->apiEndpoint = 'https://toolkit.programinity.io/api/sync';
//    }
//    else {
//      $this->apiEndpoint = 'http://toolkit-sync.test/api/sync';
//    }
  }

  public function syncPull(Request $request)
  {
    // Send data to remote endpoint
    if($request->has('force') && $request->get('force')) {
      $response = Http::withHeaders([
        'Content-Type' => 'application/json',
      ])->get($this->apiEndpoint);
    }
    else {
      $response = Http::withHeaders([
        'Content-Type' => 'application/json',
      ])->get($this->apiEndpoint, [
        'datetime' => Settings::get('last_pull_date')
      ]);
    }

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

  public function sync(Request $request) {
    try {
      $syncData = [];

      // Collect all unsynced data
      foreach ($this->tables as $table) {
        $records = $this->getUnsyncedRecords($table, $request->get('force'));

        if(!empty($records)) {
          $syncData[$table] = $records;
        }
      }

      if(empty($syncData)) {
        return ['success' => true, 'message' => 'No records to sync'];
      }

      // Send data to remote endpoint
      $response = Http::withHeaders([
        'Content-Type' => 'application/json',
      ])->post($this->apiEndpoint, [
        'data' => $syncData,
        'timestamp' => now()->toIso8601String()
      ]);

      $jsonResponse = $response->json();

      if(isset($jsonResponse['error'])) {
        return ['success' => false, 'message' => $jsonResponse['error']];
      }

      if($response->successful()) {
        // Mark records as synced
        $this->markAsSynced($syncData);
        Settings::set('last_push_date', Carbon::now());
        return ['success' => true, 'message' => 'Sync completed successfully'];
      }

      throw new \Exception('Remote sync failed: ' . $response->body());

    }
    catch (\Exception $e) {
      Log::error('Sync failed: ' . $e->getMessage());
      return ['success' => false, 'message' => 'Sync failed: ' . $e->getMessage()];
    }
  }

  protected function getUnsyncedRecords($tableName, $force = false): array
  {
    $query = DB::table($tableName);

    if(!$force && !is_null(Settings::get('last_push_date'))) {
      $query->where('updated_at', '>=', Carbon::parse(Settings::get('last_push_date')));
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

    if(auth()->user()->role === 'admin') {
      Settings::set('last_pull_date', Carbon::now());
    }
    else {
      Settings::set('last_push_date', Carbon::now());
    }
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
        ->where('updated_at', '>=', Settings::get('last_sync_date', 0))
        ->count();
    }
    return $counts;
  }
}
