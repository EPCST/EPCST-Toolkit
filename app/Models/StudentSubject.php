<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Syncable;

class StudentSubject extends Pivot {
  use Syncable;

  protected $table = 'student_subject';
  protected $guarded = [];
}
