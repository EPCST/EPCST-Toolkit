<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\DB;

class AttendanceStudent extends Pivot {
  protected $table = 'attendance_student_subject';
  protected $guarded = ['id'];

  public static function updateDropped(Subject $subject): void {
    $results = DB::table('attendance_student_subject as ass')
      ->join('students', 'ass.student_id', '=', 'students.id')
      ->join('subjects', 'ass.subject_id', '=',  'subjects.id')
      ->where('ass.subject_id', $subject->id)
      ->select('students.id', 'subjects.subject_id', 'students.first_name', 'students.last_name', 'subjects.title', DB::raw('SUM(ass.hours) as absences'))
      ->groupBy('ass.student_id', 'ass.subject_id', 'students.first_name', 'subjects.title')
      ->get();

    $droppedList = $results->filter(function($a) use ($subject) {
      return $a->absences >= $subject->dropout_threshold;
    });

    StudentSubject::whereIn('student_id', $droppedList->pluck('id'))->where('subject_id', $subject->id)->update([
      'status' => 'dropped'
    ]);

    StudentSubject::whereNotIn('student_id', $droppedList->pluck('id'))->where('subject_id', $subject->id)->update([
      'status' => 'active'
    ]);
  }
}
