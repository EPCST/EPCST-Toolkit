<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Native\Laravel\Facades\Settings;

class Subject extends Model {
  protected $guarded = [];
  protected $with = ['students', 'activities', 'attendances'];

  public function students(): BelongsToMany {
    return $this->belongsToMany(Student::class)->using(StudentSubject::class)->withPivot(['status', 'academic_year_id', 'section_id'])->orderBy('last_name');
  }

  public function attendances(): BelongsToMany {
    return $this->belongsToMany(Attendance::class, 'attendance_student_subject')->withPivot(['student_id', 'hours', 'status', 'remarks'])->groupBy('attendances.id');
  }

  public function activities(): HasMany {
    return $this->hasMany(Activity::class);
  }
}
