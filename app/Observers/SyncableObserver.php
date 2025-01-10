<?php

namespace App\Observers;

class SyncableObserver {
  public function updated($model): void {
    $model->is_synced = false;
    $model->saveQuietly(); // Save without triggering observers again
  }

  public function deleted($model): void {
    $model->is_synced = false;
    $model->deleteQuietly();
  }

  public function created($model) {
    // New records are not synced by default (is_synced = false)
  }
}
