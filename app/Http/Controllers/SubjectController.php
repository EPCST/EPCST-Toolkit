<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceStudent;
use App\Models\Student;
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
      $response = Http::withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . auth()->user()->api_token,
      ])->get($this->baseUrl . '/teacher/subjects/semesters/' . Settings::get('academic_year') . '?sectionQuery=all&mySubjects=1&page=1&search=')
        ->json();

      // Need to refresh the token, use their credentials.
      if(isset($response['message']) && $response['message'] === 'Unauthenticated') {
        // Todo: implement refresh
      }

      foreach($response['data'] as $subject) {
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

      // Attach all students to subject in one query
      $studentIds = Student::whereIn('student_no', $students->pluck('student_no'))->pluck('id');

      foreach($subject->attendances as $attendance) {
        $missingIds = $studentIds->diff(AttendanceStudent::where('attendance_id', $attendance->id)->get()->pluck('student_id'))->toArray();
        foreach($missingIds as $id) {
          AttendanceStudent::create([
            'student_id' => $id,
            'attendance_id' => $attendance->id,
            'subject_id' => $attendance->pivot->subject_id,
            'hours' => $attendance->hours,
            'status' => 'absent',
            'remarks' => 'Prefilled by System'
          ]);
        }
      }

      // TODO: [Logic] I need to understand why exactly this works. AI made this.
      $subject->students()->sync(
        collect($studentIds)->mapWithKeys(function ($id) use ($subject) {
          return [$id => [
            'subject_id' => $subject->id,
            'academic_year_id' => $subject->academic_year_id,
            'section_id' => $subject->section_id
          ]];
        })
      );

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
