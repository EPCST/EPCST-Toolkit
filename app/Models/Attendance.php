<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attendance extends Model {
  protected $guarded = ['id'];

  protected function casts(): array
  {
    return [
      'date' => 'date:Y-m-d'
    ];
  }
  public function students(): BelongsToMany {
    return $this->belongsToMany(Student::class, 'attendance_student_subject')->withPivot(['subject_id', 'is_dropped', 'return_to_class', 'hours', 'remarks', 'status']);
  }

  public function subjects(): BelongsToMany {
    return $this->belongsToMany(Subject::class, 'attendance_student_subject')->withPivot(['student_id', 'hours', 'remarks', 'status']);
  }
}
