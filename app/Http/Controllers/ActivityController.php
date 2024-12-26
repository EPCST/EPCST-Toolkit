<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityController extends Controller {
  public function index(Subject $subject) {
    $activities = $subject->activities;

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
    $allStudents = $activity->students->concat($studentsWithoutScores);

    return Inertia::render('Subjects/Activities/Show', [
      'subject' => $subject,
      'activity' => $activity->toArray(),
      'students' => $allStudents
    ]);
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

    Activity::create($formFields);
  }
}
