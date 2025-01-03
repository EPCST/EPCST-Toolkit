<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceStudent extends Pivot {
  protected $table = 'attendance_student_subject';
  protected $guarded = ['id'];

  public static function updateDropped(Subject $subject): void {
    $results = DB::table('attendance_student_subject as ass')
      ->join('attendances', 'ass.attendance_id', '=', 'attendances.id')
      ->join('students', 'ass.student_id', '=', 'students.id')
      ->join('subjects', 'ass.subject_id', '=',  'subjects.id')
      ->join('student_subject as ss', function($join) use ($subject) {
        $join->on('ass.student_id', '=', 'ss.student_id')
          ->where('ss.subject_id', $subject->id);
      })
      ->where('ass.subject_id', $subject->id)
      ->select('ss.returned_at', 'ss.status', 'attendances.date', 'students.first_name', 'students.id', 'students.last_name', 'subjects.title', 'ass.hours', DB::raw('SUM(ass.return_to_class) as hasRTC'), DB::raw('SUM(ass.hours) as absences'), DB::raw('SUM(CASE WHEN attendances.date > DATE(ss.returned_at) OR ss.returned_at IS NULL THEN ass.hours ELSE 0 END) as absences_after_return'))
      ->groupBy('ass.student_id', 'ass.subject_id', 'students.first_name', 'subjects.title')
      ->get();


    $returnList = $results->filter(function($a) {
      return $a->hasRTC && $a->absences_after_return === 0;
    });

    $droppedList = $results->filter(function($a) use ($subject) {
      return ($a->absences >= $subject->dropout_threshold && !$a->hasRTC) || ($a->status === 'return' && $a->absences_after_return > 0) || $a->absences_after_return > 0;
    });

    StudentSubject::whereIn('student_id', $droppedList->pluck('id'))->where('subject_id', $subject->id)->update([
      'status' => 'dropped'
    ]);

    StudentSubject::whereNotIn('student_id', $droppedList->pluck('id'))->where('subject_id', $subject->id)->update([
      'status' => 'active',
      'dropped_at' => null
    ]);

    StudentSubject::whereIn('student_id', $returnList->pluck('id'))->where('subject_id', $subject->id)->update(['status' => 'return', 'returned_at' => Carbon::now()]);
  }
}
