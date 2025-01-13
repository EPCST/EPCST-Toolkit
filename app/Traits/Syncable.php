<?php

namespace App\Traits;

use App\Observers\SyncableObserver;

trait Syncable
{
  public static function bootSyncable(): void
  {
    static::observe(SyncableObserver::class);
  }

  public function markAsSynced(): void
  {
    $this->is_synced = true;
    $this->saveQuietly();
  }

  public function markAsUnsynced(): void
  {
    $this->is_synced = false;
    $this->saveQuietly();
  }

  public function scopeUnsynced($query)
  {
    return $query->where('is_synced', false);
  }
}
