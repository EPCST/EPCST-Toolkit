<?php

namespace App\Observers;

use Illuminate\Support\Carbon;

class SyncableObserver {
  public function updated($model): void {
  }

  public function deleted($model): void {
    $model->deleteQuietly();
  }

  public function created($model) {
    // New records are not synced by default (is_synced = false)
  }
}
