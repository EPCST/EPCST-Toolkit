<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\HasUuid;
use App\Traits\Syncable;
class AttendanceStudent extends Pivot {
  use HasUuid, Syncable, SoftDeletes;

  protected $table = 'attendance_student_subject';
  protected $guarded = [];

  public static function updateDropped(Subject $subject): void {
    $results = DB::table('attendance_student_subject as ass')
      ->join('attendances', 'ass.attendance_id', '=', 'attendances.id')
      ->join('students', 'ass.student_no', '=', 'students.student_no')
      ->join('subjects', 'ass.subject_id', '=',  'subjects.id')
      ->join('student_subject as ss', function($join) use ($subject) {
        $join->on('ass.student_no', '=', 'ss.student_no')
          ->where('ss.subject_id', $subject->id);
      })
      ->where('ass.subject_id', $subject->id)
      ->select(
        'ss.returned_at',
        'ss.status',
        'students.first_name',
        'students.student_no',
        'students.last_name',
        'subjects.title',
        DB::raw('SUM(ass.return_to_class) as hasRTC'),
        DB::raw('SUM(ass.hours) as absences'),
        DB::raw('SUM(CASE WHEN attendances.date > DATE(ss.returned_at) OR ss.returned_at IS NULL THEN ass.hours ELSE 0 END) as absences_after_return')
      )
      ->groupBy('ass.student_no', 'ss.returned_at', 'ss.status', 'students.first_name', 'students.last_name', 'subjects.title')
      ->get();

    $returnList = $results->filter(function($a) {
      return $a->hasRTC && $a->absences_after_return === 0;
    });

    $droppedList = $results->filter(function($a) use ($subject) {
      return ($a->absences >= $subject->dropout_threshold && !$a->hasRTC) || ($a->status === 'return' && $a->absences_after_return > 0);
    });

    StudentSubject::whereIn('student_no', $droppedList->pluck('student_no'))
      ->where('subject_id', $subject->id)
      ->update(['status' => 'dropped', 'dropped_at' => Carbon::now()]);

    StudentSubject::whereNotIn('student_no', $droppedList->pluck('student_no'))
      ->where('subject_id', $subject->id)
      ->update([
        'status' => 'active',
        'dropped_at' => null
      ]);

    StudentSubject::whereIn('student_no', $returnList->pluck('student_no'))
      ->where('subject_id', $subject->id)
      ->update(['status' => 'return', 'returned_at' => Carbon::now()]);
  }
}
