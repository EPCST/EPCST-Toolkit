<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Native\Laravel\Facades\Settings;

class ReportController extends Controller {
  private const GRADE_TABLE = [
    100.0 => 1.00, 99.5 => 1.00, 99.0 => 1.00, 98.5 => 1.00, 98.0 => 1.00, 97.5 => 1.00,
    97.0 => 1.25, 96.5 => 1.25, 96.0 => 1.25, 95.5 => 1.25, 95.0 => 1.25, 94.5 => 1.25,
    94.0 => 1.50, 93.5 => 1.50, 93.0 => 1.50, 92.5 => 1.50, 92.0 => 1.50, 91.5 => 1.50,
    91.0 => 1.75, 90.5 => 1.75, 90.0 => 1.75, 89.5 => 1.75, 89.0 => 1.75, 88.5 => 1.75,
    88.0 => 2.00, 87.5 => 2.00, 87.0 => 2.00, 86.5 => 2.00, 86.0 => 2.00, 85.5 => 2.00,
    85.0 => 2.25, 84.5 => 2.25, 84.0 => 2.25, 83.5 => 2.25, 83.0 => 2.25, 82.5 => 2.25,
    82.0 => 2.50, 81.5 => 2.50, 81.0 => 2.50, 80.5 => 2.50, 80.0 => 2.50, 79.5 => 2.50,
    79.0 => 2.75, 78.5 => 2.75, 78.0 => 2.75, 77.5 => 2.75, 77.0 => 2.75, 76.5 => 2.75,
    76.0 => 3.00, 75.5 => 3.00, 75.0 => 3.00, 74.5 => 3.00
  ];

  private const CATEGORY_WEIGHTS = [
    'attendance' => 0.1,
    'activity' => 0.2,
    'quiz' => 0.3,
    'exam' => 0.4
  ];

  private const PERIOD_WEIGHTS = [
    'prelim' => 30,
    'midterm' => 30,
    'final' => 40
  ];

  public function index() {
    return Inertia::render('Reports/Index');
  }

  public function enrollmentReport() {
    $subjects = Subject::all();

    return Inertia::render('Reports/EnrollmentReport', [
      'report' => $subjects
    ]);
  }

  public function subjectLoading()
  {
    $teachers = User::select(['id', 'first_name', 'middle_name', 'last_name', 'email'])->where('role', User::ROLE_TEACHER)->get()->toArray();
    $subjects = Subject::where('academic_year_id', Settings::get('academic_year'))->get();

    return Inertia::render('Reports/SubjectLoading', [
      'report' => $subjects->groupBy('user_id'),
      'teachers' => $teachers
    ]);
  }

  public function attendanceReport() {
    $startOfWeek = Carbon::parse('2025-W05')->startOfWeek()->subDay();
    $endOfWeek = Carbon::parse('2025-W05')->endOfWeek();

    $attendanceReport = DB::table('attendance_student_subject as ass')
      ->join('students', 'ass.student_no', '=', 'students.student_no')
      ->join('subjects', 'ass.subject_id', '=', 'subjects.id')
      ->join('users', 'subjects.user_id', '=', 'users.id')
      ->join('attendances', 'ass.attendance_id', '=', 'attendances.id')
      ->where('attendances.academic_year_id', Settings::get('academic_year'))
      ->select(
        'users.id as teacher_id',
        'users.first_name as teacher_first_name',
        'users.middle_name as teacher_middle_name',
        'users.last_name as teacher_last_name',
        'users.department as teacher_department',
        'students.student_no as student_no',
        'students.first_name',
        'students.last_name',
        'subjects.id as subject_id',
        'subjects.title as subject_name',
        'subjects.code as subject_code',
        'subjects.section',
        DB::raw('SUM(ass.hours) as total_hours'),
        // Separate subquery for current week
        DB::raw("(
            SELECT COALESCE(SUM(ass2.hours), 0)
            FROM attendance_student_subject ass2
            JOIN attendances a2 ON ass2.attendance_id = a2.id
            WHERE ass2.student_no = students.student_no
            AND ass2.subject_id = subjects.id
            AND DATE(a2.date) BETWEEN '{$startOfWeek}' AND '{$endOfWeek}'
        ) as absences_this_week"),
        // Separate subquery for previous absences
        DB::raw("(
            SELECT COALESCE(SUM(ass2.hours), 0)
            FROM attendance_student_subject ass2
            JOIN attendances a2 ON ass2.attendance_id = a2.id
            WHERE ass2.student_no = students.student_no
            AND ass2.subject_id = subjects.id
            AND DATE(a2.date) < '{$startOfWeek}'
        ) as absences_before")
      )
      ->groupBy(
        'users.id',
        'students.student_no',
        'students.first_name',
        'students.last_name',
        'subjects.id',
        'subjects.title',
        'subjects.code',
        'subjects.section'
      )
      ->orderBy('students.last_name')
      ->orderBy('subjects.title')
      ->get()
      ->groupBy('student_no')
      ->mapWithKeys(function ($studentSubjects) {
        $firstRecord = $studentSubjects->first();
        return [
          $firstRecord->student_no => [
            'student' => [
              'first_name' => $firstRecord->first_name,
              'last_name' => $firstRecord->last_name,
            ],
            'subjects' => $studentSubjects->map(function ($record) {
              // Get all attendance records for this student-subject combination
              $attendanceRecords = DB::table('attendance_student_subject as ass')
                ->join('attendances', 'ass.attendance_id', '=', 'attendances.id')
                ->where('ass.student_no', $record->student_no)
                ->where('ass.subject_id', $record->subject_id)
                ->select(
                  'attendances.date',
                  'ass.status',
                  'ass.hours'
                )
                ->orderBy('attendances.date', 'desc')
                ->get();

              return [
                'code' => $record->subject_code,
                'title' => $record->subject_name,
                'section' => $record->section,
                'teacher' => [
                  'first_name' => $record->teacher_first_name,
                  'middle_name' => $record->teacher_middle_name,
                  'last_name' => $record->teacher_last_name,
                  'department' => $record->teacher_department
                ],
                'absences_this_week' => (float)$record->absences_this_week,
                'absences_before' => (float)$record->absences_before,
                'absences' => $attendanceRecords->map(function($attendance) {
                  return [
                    'date' => $attendance->date,
                    'status' => $attendance->status,
                    'hours' => (float)$attendance->hours
                  ];
                })->values()->toArray()
              ];
            })->values()
          ]
        ];
      });

    dd($attendanceReport);
  }


  public function academicReport()
  {
    $period = 'prelim';

// Define which periods to include based on current period
    $periodsToInclude = match ($period) {
      'prelim' => ['prelim'],
      'midterm' => ['prelim', 'midterm'],
      'final' => ['prelim', 'midterm', 'final'],
      default => throw new \Exception('Invalid period specified')
    };

    $activityRecords = DB::table('activities')
      ->join('subjects', 'activities.subject_id', '=', 'subjects.id')
      ->leftJoin('activity_student', 'activities.id', '=', 'activity_student.activity_id')
      ->leftJoin('students', 'activity_student.student_id', '=', 'students.id')
      ->where('activities.academic_year_id', Settings::get('academic_year'))
      ->whereIn('activities.period', $periodsToInclude)
      ->select(
        'subjects.id as subject_id',
        'subjects.title as subject_name',
        'subjects.code as subject_code',
        'activities.id as activity_id',
        'activities.title as activity_title',
        'activities.type',
        'activities.period',
        'activities.points',
        'students.id as student_id',
        'students.first_name',
        'students.last_name',
        'students.student_no',
        'activity_student.score'
      )
      ->orderBy('subjects.title')
      ->orderBy('students.last_name')
      ->get()
      ->groupBy('subject_id')
      ->map(function ($subjectActivities) {
        $firstRecord = $subjectActivities->first();

        return [
          'subject_id' => $firstRecord->subject_id,
          'subject_name' => $firstRecord->subject_name,
          'subject_code' => $firstRecord->subject_code,
          'activities' => $subjectActivities
            ->groupBy('activity_id')
            ->map(function ($activityRecords) {
              $activity = $activityRecords->first();

              return [
                'id' => $activity->activity_id,
                'title' => $activity->activity_title,
                'type' => $activity->type,
                'period' => $activity->period,
                'students' => $activityRecords
                  ->filter(fn($record) => !is_null($record->student_id))
                  ->map(function ($record) {
                    return [
                      'student_id' => $record->student_id,
                      'student_no' => $record->student_no,
                      'first_name' => $record->first_name,
                      'last_name' => $record->last_name,
                      'score' => $record->score,
                    ];
                  })->values(),
              ];
            })->values()
        ];
      })->values();

    dd($activityRecords);
  }

  public function dropoutReport(Request $request)
  {
    // Get start and end dates from request or default to current month
    $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
    $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

    $droppedStudents = DB::table('student_subject as ss')
      ->join('students', 'ss.student_id', '=', 'students.id')
      ->join('subjects', 'ss.subject_id', '=', 'subjects.id')
      ->where('ss.status', '=', 'dropped')
      ->where('ss.academic_year_id', Settings::get('academic_year'))
      ->whereBetween('ss.dropped_at', [$startDate, $endDate])
      ->select(
        'subjects.id as subject_id',
        'subjects.title as subject_name',
        'subjects.code as subject_code',
        'subjects.section',
        'students.id as student_id',
        'students.student_no',
        'students.first_name',
        'students.last_name',
        'ss.dropped_at',
        DB::raw('(
                SELECT COALESCE(SUM(ass.hours), 0)
                FROM attendance_student_subject ass
                JOIN attendances a ON ass.attendance_id = a.id
                WHERE ass.student_id = students.id
                AND ass.subject_id = subjects.id
                AND a.academic_year_id = ss.academic_year_id
            ) as total_absences'),
        DB::raw('(
                SELECT MAX(a.date)
                FROM attendance_student_subject ass
                JOIN attendances a ON ass.attendance_id = a.id
                WHERE ass.student_id = students.id
                AND ass.subject_id = subjects.id
                AND a.academic_year_id = ss.academic_year_id
                AND ass.status = "present"
            ) as last_attendance_date')
      )
      ->orderBy('subjects.title')
      ->orderBy('students.last_name')
      ->get()
      ->groupBy('subject_id')
      ->map(function ($studentsInSubject) {
        $firstRecord = $studentsInSubject->first();
        return [
          'subject_name' => $firstRecord->subject_name,
          'subject_code' => $firstRecord->subject_code,
          'section' => $firstRecord->section,
          'students' => $studentsInSubject->map(function ($record) {
            return [
              'id' => $record->student_id,
              'student_no' => $record->student_no,
              'first_name' => $record->first_name,
              'last_name' => $record->last_name,
              'dropped_at' => Carbon::parse($record->dropped_at)->format('M d, Y'),
              'total_absences' => (float)$record->total_absences,
              'last_attendance_date' => $record->last_attendance_date
            ];
          })->values()
        ];
      })->values();
  }

  public function gradeReport(Request $request)
  {
    $academicYearId = Settings::get('academic_year');
    $currentPeriod = $request->get('period', 'final');
    $periods = ['prelim', 'midterm', 'final'];

    // Get all subjects with their students
    $subjects = Subject::with('students')
      ->whereHas('students')
      ->where('academic_year_id', $academicYearId)
      ->get();

    // Initialize the structure
    $gradeData = [];

    foreach ($subjects as $subject) {
      foreach ($subject->students as $student) {
        if (!isset($gradeData[$subject->id])) {
          $gradeData[$subject->id] = [
            'subject' => $subject,
            'students' => []
          ];
        }

        $gradeData[$subject->id]['students'][$student->id] = [
          'student' => $student,
          'prelim' => $this->initializePeriodStructure(),
          'midterm' => $this->initializePeriodStructure(),
          'final' => $this->initializePeriodStructure()
        ];
      }
    }

    // Get attendance data
    $attendances = DB::table('attendance_student_subject as ass')
      ->join('attendances', 'ass.attendance_id', '=', 'attendances.id')
      ->where('attendances.academic_year_id', $academicYearId)
      ->select(
        'ass.subject_id',
        'ass.student_id',
        'attendances.period',
        'ass.status',
        DB::raw('SUM(ass.hours) as total_hours'),
        DB::raw('COUNT(*) as total_meetings')
      )
      ->groupBy('ass.subject_id', 'ass.student_id', 'attendances.period', 'ass.status')
      ->get();

    // Get activities data (including quizzes and exams)
    $activities = DB::table('activities')
      ->join('activity_student', 'activities.id', '=', 'activity_student.activity_id')
      ->where('activities.academic_year_id', $academicYearId)
      ->select(
        'activities.subject_id',
        'activity_student.student_id',
        'activities.period',
        'activities.type',
        DB::raw('SUM(activity_student.score) as total_score'),
        DB::raw('SUM(activities.points) as total_possible'),
        DB::raw('COUNT(*) as activity_count')
      )
      ->groupBy('activities.subject_id', 'activity_student.student_id', 'activities.period', 'activities.type')
      ->get();

    // Populate attendance data
    foreach ($attendances as $attendance) {
      if(isset($gradeData[$attendance->subject_id]['students'][$attendance->student_id])) {
        $periodData = &$gradeData[$attendance->subject_id]['students'][$attendance->student_id][$attendance->period]['attendances'];
        if($attendance->status === 'absent') {
          $periodData['absences'] = $attendance->total_hours;
        }
        $periodData['totalMeet'] += $attendance->total_hours;
      }
    }

    // Populate activities data
    foreach ($activities as $activity) {
      if(isset($gradeData[$activity->subject_id]['students'][$activity->student_id])) {
        $studentPeriod = &$gradeData[$activity->subject_id]['students'][$activity->student_id][$activity->period];

        switch ($activity->type) {
          case 'activity':
            $studentPeriod['activities']['score'] = $activity->total_score;
            $studentPeriod['activities']['total'] = $activity->total_possible;
            break;
          case 'quiz':
            $studentPeriod['quizzes']['score'] = $activity->total_score;
            $studentPeriod['quizzes']['total'] = $activity->total_possible;
            break;
          case $activity->period: // For exams (type matches period)
            $studentPeriod['exams']['score'] = $activity->total_score;
            $studentPeriod['exams']['total'] = $activity->total_possible;
            break;
        }
      }
    }

    // Calculate grades for each subject and student
    foreach ($gradeData as &$subjectData) {
      foreach ($subjectData['students'] as &$studentData) {
        $finalGrade = 0;

        // Only process periods up to current period
        $periodsToProcess = array_slice(
          $periods,
          0,
          array_search($currentPeriod, $periods) + 1
        );

        foreach ($periodsToProcess as $period) {
          $periodData = &$studentData[$period];

          // Calculate category grades
          $categoryGrades = [
            'attendance' => $this->calculateAttendanceGrade(
              $periodData['attendances']['totalMeet'] ?? 0,
              $periodData['attendances']['absences'] ?? 0
            ),
            'activity' => $this->calculateComponentGrade(
              $periodData['activities']['score'] ?? 0,
              $periodData['activities']['total'] ?? 0
            ),
            'quiz' => $this->calculateComponentGrade(
              $periodData['quizzes']['score'] ?? 0,
              $periodData['quizzes']['total'] ?? 0
            ),
            'exam' => $this->calculateComponentGrade(
              $periodData['exams']['score'] ?? 0,
              $periodData['exams']['total'] ?? 0
            )
          ];

          // Calculate period grade with category weights
          $periodGrade = 0;
          foreach (self::CATEGORY_WEIGHTS as $category => $weight) {
            $periodGrade += $categoryGrades[$category] * $weight;
          }

          // Store detailed grade information
          $periodData['grades'] = [
            'categories' => $categoryGrades,
            'raw' => round($periodGrade, 2),
            'weighted' => round($periodGrade * (self::PERIOD_WEIGHTS[$period] / 100), 2),
            'numerical' => $this->translateGrade($periodGrade)
          ];

          // Add to final grade
          $finalGrade += $periodGrade * (self::PERIOD_WEIGHTS[$period] / 100);
        }

        // Add final grade to student data
        $studentData['final_grade'] = [
          'raw' => round($finalGrade, 2),
          'numerical' => $this->translateGrade($finalGrade)
        ];
      }
    }

    dd($gradeData);
  }

  private function initializePeriodStructure(): array
  {
    return [
      'attendances' => [
        'absences' => 0,
        'totalMeet' => 0
      ],
      'activities' => [
        'score' => 0,
        'total' => 0
      ],
      'quizzes' => [
        'score' => 0,
        'total' => 0
      ],
      'exams' => [
        'score' => 0,
        'total' => 0
      ]
    ];
  }

  private function calculateAttendanceGrade(float $totalMeet, float $absences): float
  {
    if ($totalMeet == 0) return 0;
    return (($totalMeet - $absences) / $totalMeet) * 100;
  }

  private function calculateComponentGrade(float $score, float $total): float
  {
    if ($total == 0) return 0;
    return ($score / $total) * 100;
  }

  private function translateGrade(float $score): float
  {
    // Round to nearest 0.5 to match table increments
    $score = round($score * 2) / 2;

    // Cap at 100
    $score = min($score, 100.0);

    // If score exists in table, return corresponding grade
    if (isset(self::GRADE_TABLE[$score])) {
      return self::GRADE_TABLE[$score];
    }

    // If score is below lowest table entry, return 5.0
    return 5.00;
  }
}
