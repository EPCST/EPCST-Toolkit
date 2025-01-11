<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityStudent;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Native\Laravel\Facades\Settings;

class ActivityController extends Controller {
  public function index(Subject $subject) {
    $activities = $subject->activities->where('period', Settings::get('period'))->groupBy('type');

    return Inertia::render('Subjects/Activities/List', [
      'subject' => $subject,
      'activities' => $activities
    ]);
  }

  public function show(Subject $subject, Activity $activity) {
    $activity->load(['students' => function ($query) {
      $query->select('students.*', 'activity_student.score')->withPivot('score');
    }]);

    // Get all students from subject that aren't in the activity
    $studentsWithoutScores = $subject->students()
      ->whereNotIn('students.student_no', $activity->students->pluck('student_no'))
      ->get();

    // Merge both collections
    $allStudents = $activity->students->concat($studentsWithoutScores)->sortBy('last_name')->values()->all();

    return Inertia::render('Subjects/Activities/Show', [
      'subject' => $subject,
      'activity' => $activity->toArray(),
      'students' => $allStudents
    ]);
  }

  public function update(Request $request, Subject $subject, Activity $activity) {
    $upsertPayload = [];

    $activity->update([
      'title' => $request->post('title'),
      'description' => $request->post('description'),
      'due_date' => $request->post('due_date'),
      'points' => $request->post('points')
    ]);

    foreach($request->post('studentsScores') as $student) {
      $upsertPayload[] = [
        'activity_id' => $activity['id'],
        'student_no' => $student['student_no'],
        'score' => $student['score'],
        'remarks' => $student['remarks']
      ];
    }

    ActivityStudent::upsert($upsertPayload, uniqueBy: ['activity_id', 'student_no'], update: ['score', 'remarks']);
  }

  public function create(Request $request, Subject $subject) {
    return Inertia::render('Subjects/Activities/Create', [
      'subject' => $subject,
      'type' => $request->get('type'),
      'students' => $subject->students->sortBy('last_name')->values()->all()
    ]);
  }

  public function destroy(Request $request, Subject $subject, Activity $activity) {
    $activity->update(['mark_deleted' => true]);
  }

  public function store(Request $request) {
    $formFields = $request->validate([
      'title' => 'required',
      'subject_id' => 'required',
      'period' => 'required',
      'academic_year_id' => 'required',
      'points' => 'required|integer|min:1',
      'due_date' => 'required'
    ]);

    $formFields['type'] = $request->post('type');
    $activity = Activity::create($formFields);

    $upsertPayload = [];
    foreach($request->post('studentsScores') as $student) {
      $upsertPayload[] = [
        'activity_id' => $activity['id'],
        'student_no' => $student['student_no'],
        'score' => $student['score'],
        'remarks' => $student['remarks']
      ];
    }

    ActivityStudent::upsert($upsertPayload, uniqueBy: ['activity_id', 'student_no'], update: ['score', 'remarks']);
  }
}
