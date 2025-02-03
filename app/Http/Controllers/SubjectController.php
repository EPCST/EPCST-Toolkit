<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceStudent;
use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\Subject;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Native\Laravel\Facades\Settings;

class SubjectController extends Controller
{
  protected string $baseUrl = 'https://gradebook.epcst.edu.ph/api';

  public function fetchSubjects() {
    try {
      $subjects = [];

      $i = 1;
      $subjects[] = Http::withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . auth()->user()->api_token,
      ])->get($this->baseUrl . '/teacher/subjects/semesters/' . Settings::get('academic_year') . '?sectionQuery=all&mySubjects=1&page=' . $i . '&search=')
        ->json()['data'];

      $ceil = ceil($response['total'] / 10);
      if($ceil > 1) {
        for($i = 2; $i <= $ceil; $i++) {
          $subjects[] = Http::withHeaders([
            'accept' => 'application/json',
            'Authorization' => 'Bearer ' . auth()->user()->api_token,
          ])->get($this->baseUrl . '/teacher/subjects/semesters/' . Settings::get('academic_year') . '?sectionQuery=all&mySubjects=1&page=' . $ceil . '&search=')
            ->json()['data'];
        }
      }

      // Need to refresh the token, use their credentials.
      if(isset($response['message']) && $response['message'] === 'Unauthenticated') {
        // Todo: implement refresh
      }

      foreach($subjects as $subject) {
        Subject::createOrFirst([
          'title' => $subject['title'],
          'section' => $subject['section_name'],
          'subject_id' => $subject['id'],
          'academic_year_id' => Settings::get('academic_year'),
          'section_id' => $subject['section_id'],
        ]);
      }

      $subjects = Subject::where('academic_year_id', Settings::get('academic_year'))->get()->groupBy('title');

      return Inertia::render('Subjects/List', [
        'subjects' => $subjects
      ]);
    } catch (Exception $e) {
      return redirect()->back()->withErrors(['generic' => $e->getMessage()]);
    }
  }

  public function fetchStudents(Subject $subject) {
    try {
      $response = Http::withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . auth()->user()->api_token,
      ])->get($this->baseUrl . "/subjects/{$subject->subject_id}/students/{$subject->academic_year_id}/sections/{$subject->section_id}")
        ->json();

      // Need to refresh the token, use their credentials.
      if (isset($response['message']) && $response['message'] === 'Unauthenticated') {
        // Todo: implement refresh
      }

      $students = collect($response['data'])->map(function ($student) {
        $fullNameExplode = explode(', ', $student['full_name']);
        return [
          'student_no' => $student['student_number'],
          'email' => $student['email'],
          'gender' => $student['gender'],
          'course_name' => $student['course_name'],
          'date_of_birth' => $student['date_of_birth'],
          'first_name' => $fullNameExplode[1],
          'last_name' => $fullNameExplode[0],
        ];
      });

      // Bulk insert students
      Student::upsert($students->toArray(), ['student_no'], [
        'email',
        'gender',
        'course_name',
        'date_of_birth',
        'first_name',
        'last_name'
      ]);

      // Create student_subject relationships
      foreach($students as $student) {
        StudentSubject::updateOrCreate(
          [
            'student_no' => $student['student_no'],
            'subject_id' => $subject->id,
            'academic_year_id' => $subject->academic_year_id,
            'section_id' => $subject->section_id
          ]
        );
      }

      // Handle attendance records
      $studentNos = $students->pluck('student_no');
      foreach($subject->attendances as $attendance) {
        $missingNos = $studentNos->diff(
          AttendanceStudent::where('attendance_id', $attendance->id)
                          ->pluck('student_no')
        )->toArray();

        foreach($missingNos as $studentNo) {
          AttendanceStudent::create([
            'student_no' => $studentNo,  // Changed from student_id
            'attendance_id' => $attendance->id,
            'subject_id' => $subject->id,
            'hours' => $attendance->hours,
            'status' => 'absent',
            'remarks' => 'Prefilled by System'
          ]);
        }
      }

      return redirect()->back()->with([
        'subject' => $subject,
        'students' => $subject->students,
      ]);
    } catch (Exception $e) {
      return redirect()->back()->withErrors(['generic' => $e->getMessage()]);
    }
  }

  public function index() {
    $subjects = Subject::where('academic_year_id', Settings::get('academic_year'))->get()->groupBy('title');

    return inertia('Subjects/List', [
      'subjects' => $subjects
    ]);
  }

  public function show(Subject $subject) {
    return inertia('Subjects/Show', [
      'subject' => $subject,
      'attendances' => $subject->attendances->where('period', Settings::get('period'))->values()->toArray(),
      'activities' => $subject->activities->where('period', Settings::get('period'))->values()->toArray(),
      'students' => $subject->students->sortBy('last_name')->values()->toArray()]);
  }

  public function delete() {

  }

  public function update(Request $request, Subject $subject) {
    $subject->update([
      'code' => $request->post('code'),
      'title' => $request->post('title'),
      'attendance_threshold' => $request->post('attendance_threshold'),
      'dropout_threshold' => $request->post('dropout_threshold'),
      'units_lab' => $request->post('units_lab'),
      'units_lec' => $request->post('units_lec'),
    ]);
  }
}
