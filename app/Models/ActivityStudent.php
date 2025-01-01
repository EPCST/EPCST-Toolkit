<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ActivityStudent extends Pivot {
  protected $table = 'activity_student';

  protected $fillable = ['activity_id', 'student_id', 'remarks', 'score'];
}
