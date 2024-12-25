<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model {
  protected $guarded = [];
  protected $with = ['students'];

  public function students(): BelongsToMany {
    return $this->belongsToMany(Student::class)->using(StudentSubject::class)->withPivot(['academic_year_id', 'section_id']);
  }
}
