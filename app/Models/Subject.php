<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model {
  protected $guarded = [];
  protected $with = ['students', 'activities'];

  public function students(): BelongsToMany {
    return $this->belongsToMany(Student::class)->using(StudentSubject::class)->withPivot(['academic_year_id', 'section_id']);
  }

  public function activities(): HasMany {
    return $this->hasMany(Activity::class);
  }
}
