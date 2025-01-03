<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceStudent;
use App\Models\StudentSubject;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Native\Laravel\Facades\Settings;

class AttendanceController extends Controller {
  public function index(Subject $subject) {
    $attendances = $subject->attendances()->with('students')->where('academic_year_id', Settings::get('academic_year'))->where('period', Settings::get('period'))->orderBy('date', 'ASC')->groupBy('date')->get();
    AttendanceStudent::updateDropped($subject);

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

  public function edit(Request $request, Subject $subject, Attendance $attendance) {
    $attendance = Attendance::with(['students'])->find($attendance->id);

    return Inertia::render('Subjects/Attendances/Edit', [
      'attendance' => $attendance,
      'subject' => $subject
    ]);
  }

  public function update(Request $request, Subject $subject, Attendance $attendance) {
    $formFields = $request->validate([
      'date' => 'required|date',
      'hours' => 'integer|min:1|max:5'
    ]);

    $formFields['period'] = Settings::get('period');
    $formFields['academic_year_id'] = Settings::get('academic_year');

    $attendance->update($formFields);

    foreach($request->post('attendanceData') as $studentAttendance) {
      AttendanceStudent::where('attendance_id', $attendance->id)->where('subject_id', $subject->id)->where('student_id', $studentAttendance['student_id'])->update([
        'remarks' => $studentAttendance['remarks'],
        'hours' => $studentAttendance['hours'],
        'status' => $studentAttendance['status']
      ]);
    }
    AttendanceStudent::updateDropped($subject);
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
    AttendanceStudent::updateDropped($subject);
  }
}
