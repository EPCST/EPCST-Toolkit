<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\HasUuid;
use App\Traits\Syncable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model {
  use HasUuid, Syncable, SoftDeletes;

  protected $guarded = [];

  protected $with = ['students'];

  protected function casts(): array
  {
    return [
      'due_date' => 'datetime:M d, Y h:i A',
    ];
  }

  public function subject(): BelongsTo {
    return $this->belongsTo(Subject::class);
  }

  public function students(): BelongsToMany {
    return $this->belongsToMany(Student::class, 'activity_student', 'activity_id', 'student_no')
      ->using(ActivityStudent::class)
      ->withPivot(['score', 'remarks']);
  }
}
