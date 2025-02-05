<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubjectController;
use App\Models\Activity;
use App\Models\Attendance;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Native\Laravel\Facades\Settings;


Route::redirect('/', '/login')->name('home');
Route::middleware('guest')->group(function () {
  Route::get('/login', [AuthController::class, 'login'])->name('login');
  Route::post('/login', [AuthController::class, 'auth'])->name('login.auth');
});

Route::middleware('auth')->group(function(){
  Route::get('/dashboard', function(Request $request) {
    $isAdmin = auth()->user()->role === 'admin';
    $lastSync = $isAdmin ? Carbon::parse(Settings::get('last_pull_date'))->diffForHumans() : Carbon::parse(Settings::get('last_push_date'))->diffForHumans();

    $extra = [];
    if($isAdmin) {
      $extra['users'] = User::where('role', 2)->get();
      $extra['subjects'] = Subject::all()->groupBy('user_id');
      $extra['activities'] = Activity::all()->groupBy('user_id');
      $extra['attendances'] = Attendance::all()->groupBy('user_id');
    }

    return inertia('Dashboard', [
      'subjectCount' => Subject::where('academic_year_id', Settings::get('academic_year'))->count(),
      'lastSync' => $lastSync,
      ...$extra
    ]);
  })->name('dashboard');

  Route::post('/settings', function(Request $request) {
    foreach($request->all() as $key => $value) {
      Settings::set($key, $value);
    }
  });

  Route::get('/sync', function(Request $request){
    $dbs = new \App\Services\DatabaseSyncService();
    if(auth()->user()->role === 'admin') {
      $dbs->syncPull($request);
    }
    else if(auth()->user()->role === 'teacher') {
      $dbs->sync($request);
    }

    return redirect()->back()->with([

    ]);
  })->name('sync');

  Route::group(['prefix' => 'reports'], function(){
    Route::get('/', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/enrollment-report', [ReportController::class, 'enrollmentReport'])->name('reports.enrollmentReport');
    Route::get('/subject-loading', [ReportController::class, 'subjectLoading'])->name('reports.subjectLoading');
    Route::get('/attendance-report', [ReportController::class, 'attendanceReport'])->name('reports.attendanceReport');
    Route::get('/academic-report', [ReportController::class, 'academicReport'])->name('reports.academicReport');
    Route::get('/dropout-report', [ReportController::class, 'dropoutReport'])->name('reports.dropoutReport');
    Route::get('/grade-report', [ReportController::class, 'gradeReport'])->name('reports.gradeReport');
  });

  Route::group(['prefix' => 'subjects'], function(){
    Route::get('/', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/fetch', [SubjectController::class, 'fetchSubjects'])->name('subjects.fetchSubjects');
    Route::get('/{subject}', [SubjectController::class, 'show'])->name('subjects.show');
    Route::post('/{subject}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/{subject}', [SubjectController::class, 'delete'])->name('subjects.delete');
    Route::get('/{subject}/attendances', [AttendanceController::class, 'index'])->name('subjects.attendances.index');
    Route::get('/{subject}/attendances/create', [AttendanceController::class, 'create'])->name('subjects.attendances.create');
    Route::post('/{subject}/attendances/create', [AttendanceController::class, 'store'])->name('subjects.attendances.store');
    Route::get('/{subject}/attendances/{attendance}', [AttendanceController::class, 'edit'])->name('subjects.attendances.edit');
    Route::post('/{subject}/attendances/{attendance}', [AttendanceController::class, 'update'])->name('subjects.attendances.update');
    Route::get('/{subject}/activities', [ActivityController::class, 'index'])->name('subjects.activities.index');
    Route::get('/{subject}/activities/create', [ActivityController::class, 'create'])->name('subjects.activities.create');
    Route::post('/{subject}/activities/create', [ActivityController::class, 'store'])->name('subjects.activities.store');
    Route::get('/{subject}/activities/{activity}', [ActivityController::class, 'show'])->name('subjects.activities.show');
    Route::post('/{subject}/activities/{activity}', [ActivityController::class, 'update'])->name('subjects.activities.update');
    Route::delete('/{subject}/activities/{activity}/delete', [ActivityController::class, 'destroy'])->name('subjects.activities.destroy');
    Route::get('/{subject}/fetch', [SubjectController::class, 'fetchStudents'])->name('subjects.fetchStudents');
  });

  Route::get('/logout', function(Request $request) {
    Auth::logout();

    return redirect()->route('home');
  })->name('logout');
});
