<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Activity extends Model {
  protected $guarded = [];

  protected $with = ['students'];

  protected function casts(): array
  {
    return [
      'due_date' => 'datetime:M d, Y',
    ];
  }

  public function subject(): BelongsTo {
    return $this->belongsTo(Subject::class);
  }

  public function students(): BelongsToMany {
    return $this->belongsToMany(Student::class);
  }
}
