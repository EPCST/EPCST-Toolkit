<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityStudent;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityController extends Controller {
  public function index(Subject $subject) {
    $activities = $subject->activities->groupBy('type');

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
      ->whereNotIn('students.id', $activity->students->pluck('id'))
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

    foreach($request->post('studentsScores') as $student) {
      $upsertPayload[] = [
        'activity_id' => $activity['id'],
        'student_id' => $student['id'],
        'score' => $student['score'],
        'remarks' => $student['remarks']
      ];
    }

    ActivityStudent::upsert($upsertPayload, uniqueBy: ['activity_id', 'student_id'], update: ['score', 'remarks']);
  }

  public function store(Request $request) {
    $formFields = $request->validate([
      'title' => 'required',
      'subject_id' => 'required',
      'period' => 'required',
      'academic_year_id' => 'required',
      'type' => 'required',
      'points' => 'required|integer|min:1',
      'due_date' => 'required'
    ]);

    $activity = Activity::create($formFields);
  }
}
