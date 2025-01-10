<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\Syncable;

class Student extends Model {
  use Syncable;

  protected $keyType = 'string';
  protected $primaryKey = 'student_no';
  protected $with = ['attendances'];
  public $incrementing = false;

  protected $guarded = [];

  public function subjects(): BelongsToMany {
    return $this->belongsToMany(Subject::class, 'student_subject', 'student_no', 'subject_id')
                ->withPivot(['status', 'returned_at', 'dropped_at', 'academic_year_id', 'section_id']);
  }

  public function attendances(): BelongsToMany {
    return $this->belongsToMany(Attendance::class, 'attendance_student_subject', 'student_no', 'attendance_id')
                ->withPivot(['subject_id', 'return_to_class', 'is_dropped', 'hours', 'status', 'remarks']);
  }

  public function activities(): BelongsToMany {
    return $this->belongsToMany(Activity::class, 'activity_student', 'student_no', 'activity_id')
                ->withPivot(['score', 'remarks']);
  }
}
