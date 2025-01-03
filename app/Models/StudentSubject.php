<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class StudentSubject extends Pivot {
  protected $table = 'student_subject';
  protected $guarded = [];
}
