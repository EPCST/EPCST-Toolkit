<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Syncable;

class ActivityStudent extends Pivot {
  use Syncable;
    protected $table = 'activity_student';
    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_no', 'student_no');
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'id');
    }
}
