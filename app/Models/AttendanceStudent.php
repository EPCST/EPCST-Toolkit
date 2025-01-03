<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AttendanceStudent extends Pivot {
  protected $table = 'attendance_student_subject';

  protected $guarded = ['id'];

}
