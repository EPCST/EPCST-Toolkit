<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model {

  protected $with = ['attendances'];

  protected $guarded = [];

  public function subjects(): BelongsToMany {
    return $this->belongsToMany(Subject::class);
  }

  public function attendances(): BelongsToMany {
    return $this->belongsToMany(Attendance::class, 'attendance_student_subject')->withPivot(['subject_id', 'hours', 'status', 'remarks']);
  }

  public function activities(): BelongsToMany {
    return $this->belongsToMany(Activity::class)->using(ActivityStudent::class)->withPivot(['score', 'remarks']);
  }
}
