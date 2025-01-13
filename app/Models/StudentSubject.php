<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Syncable;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentSubject extends Pivot {
  use Syncable, SoftDeletes;

  protected $table = 'student_subject';
  protected $guarded = [];
}
