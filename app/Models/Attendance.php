<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\HasUuid;
use App\Traits\Syncable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model {
  use HasUuid, Syncable, SoftDeletes;

  protected $guarded = ['id'];

  protected function casts(): array
  {
    return [
      'date' => 'date:Y-m-d'
    ];
  }
  public function students(): BelongsToMany {
    return $this->belongsToMany(Student::class, 'attendance_student_subject', 'attendance_id', 'student_no')
                ->withPivot(['subject_id', 'is_dropped', 'return_to_class', 'hours', 'remarks', 'status']);
  }

  public function subjects(): BelongsToMany {
    return $this->belongsToMany(Subject::class, 'attendance_student_subject', 'attendance_id', 'subject_id')
                ->withPivot(['student_no', 'hours', 'remarks', 'status']);
  }
}
