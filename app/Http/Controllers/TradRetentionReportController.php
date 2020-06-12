<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

class TradRetentionReportController extends BaseController
{
    public function index()
    {
        // From BaseController!
        $students = $this->students;

        return view('retention.index', compact('students'));
    }

    public function show($studentId)
    {
      // From BaseController!
      $students = $this->students;

      $student = $students->where('DFLT_ID', $studentId)->first();

      // dd($student);
      // dd($studentId);

      return view('retention.show', compact('student'));

    }

}
