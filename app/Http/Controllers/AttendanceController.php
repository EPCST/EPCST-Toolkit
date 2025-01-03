<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceStudent;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Native\Laravel\Facades\Settings;

class AttendanceController extends Controller {
  public function index(Subject $subject) {
    $attendances = $subject->attendances()->with('students')->where('academic_year_id', Settings::get('academic_year'))->where('period', Settings::get('period'))->orderBy('date', 'ASC')->groupBy('date')->get();

    return Inertia::render('Subjects/Attendances/List', [
      'subject' => $subject,
      'attendances' => $attendances
    ]);
  }

  public function create(Subject $subject) {
    return Inertia::render('Subjects/Attendances/Create', [
      'subject' => $subject
    ]);
  }

  public function store(Request $request, Subject $subject) {
    $formFields = $request->validate([
      'date' => 'required|date',
      'hours' => 'integer|min:1|max:5'
    ]);

    $formFields['period'] = Settings::get('period');
    $formFields['academic_year_id'] = Settings::get('academic_year');

    $attendance = Attendance::create($formFields);

    $insertPayload = [];
    foreach($request->post('attendanceData') as $studentAttendance) {
      $insertPayload[] = [
        'attendance_id' => $attendance->id,
        'subject_id' => $subject->id,
        ...$studentAttendance
      ];
    }

    AttendanceStudent::insertOrIgnore($insertPayload);
  }
}
