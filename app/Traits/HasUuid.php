<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
  /**
   * Boot the trait
   */
  protected static function bootHasUuid()
  {
    static::creating(function ($model) {
      $keyName = $model->getKeyName();

      // Only set UUID if the key isn't already set and the key name is 'id'
      if (!$model->getKey() && $keyName === 'id') {
        $model->setAttribute($keyName, Str::uuid()->toString());
      }
    });
  }

  /**
   * Override the getIncrementing() method to return false
   */
  public function getIncrementing()
  {
    return false;
  }

  /**
   * Override the getKeyType() method to return 'string'
   */
  public function getKeyType()
  {
    return 'string';
  }
}
