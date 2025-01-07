<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Native\Laravel\Facades\Settings;
use App\Traits\HasUuid;

class Subject extends Model {
  use HasUuid;
  protected $guarded = [];
  protected $with = ['students', 'activities', 'attendances'];

  public function students(): BelongsToMany {
    return $this->belongsToMany(Student::class, 'student_subject', 'subject_id', 'student_no')
                ->withPivot(['status', 'returned_at', 'dropped_at', 'academic_year_id', 'section_id'])
                ->orderBy('last_name');
  }

  public function attendances(): BelongsToMany {
    return $this->belongsToMany(Attendance::class, 'attendance_student_subject', 'subject_id', 'attendance_id')
                ->withPivot(['student_no', 'is_dropped', 'return_to_class', 'hours', 'status', 'remarks'])
                ->groupBy('attendances.id');
  }

  public function activities(): HasMany {
    return $this->hasMany(Activity::class);
  }
}
