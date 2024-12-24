<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubjectController extends Controller {
  public function index() {
    return inertia('Subjects/List');
  }

  public function show(string $subject) {
    return inertia('Subjects/Show', ['subject' => $subject]);
  }
}
