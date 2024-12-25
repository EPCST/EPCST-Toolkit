<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model {

  protected $guarded = [];

  public function subjects(): BelongsToMany {
    return $this->belongsToMany(Subject::class);
  }
}
