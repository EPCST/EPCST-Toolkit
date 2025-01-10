<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

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
    'attendance_student_subject'
  ];

  public function __construct()
  {
    $this->apiEndpoint = 'http://127.0.0.1:8000/api/sync';
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
    return DB::table($tableName)
      ->where('is_synced', false)
      ->get()
      ->map(function ($record) use ($tableName) {
        $data = (array) $record;
        if(!in_array($tableName, ['users', 'students'])) {
          $data['user_id'] = auth()->user()->id;
        }

        unset($data['is_synced']);
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
        $primaryKey = $this->getPrimaryKeyForTable($table);
        $ids = array_column($records, $primaryKey);

        DB::table($table)
          ->whereIn($primaryKey, $ids)
          ->update(['is_synced' => true]);
      }

      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      throw $e;
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
        ->where('is_synced', false)
        ->count();
    }
    return $counts;
  }
}
