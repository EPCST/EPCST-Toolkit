<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Native\Laravel\Facades\Settings;
use App\Traits\HasUuid;
use App\Traits\Syncable;

class Subject extends Model {
  use HasUuid, Syncable, SoftDeletes;

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

  public function teacher(): BelongsTo {
    return $this->belongsTo(User::class, 'user_id')->select('id', 'first_name', 'last_name', 'middle_name', 'email', 'department');
  }

  public function activities(): HasMany {
    return $this->hasMany(Activity::class);
  }
}
