<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Queries\TradEnrolled;
use Carbon\Carbon;


class BaseController extends Controller
{
    protected $students;
    // public $this->students = $students;

    public function __construct()
    {
        // build up Spring 2020 Trad Enrolled dataset/collection
        $term = '20192';
        $results = TradEnrolled::get($term);

        // dd($results);

        $nonReturners = $results->filter(function($student) {
            return $student->IsAOrWInNextTerm == 0;
        });

        // $returners = $results->filter(function($student) {
        //     return $student->IsAOrWInNextTerm == 1;
        // });

        // determine if non-returning student is an expected August 2020 Bachelors graduate or
        // if they earned a Bachelors dergee in May 2020.
        $nonReturnersWithEligibleStatus = $nonReturners->map(function($student) {
          $student->eligibilityStatus = $this->determine_eligibility_status($student);
          return $student;
        });

        //rename variable
        // $students = $nonReturnersWithEligibleStatus;

        // want "eligible" TRAD non-returners!
        $this->students = $nonReturnersWithEligibleStatus->filter(function($student) {
            return (! $student->eligibilityStatus);
        });

        // dd($students);

    }

    private function determine_eligibility_status($student)
    {
      $date_earned_degree = Carbon::parse($student->dateEarnedMostRecentBachelors);
      $date_degree_expected = Carbon::parse($student->expectedDateDegree);

      // Test for is a May 2020 Bachelors graduate
      if( ($date_earned_degree->year == 2020) && ($date_earned_degree->month == 5) )
      {
          return true;
      }

      // Test for is an expected August 2020 Bachelors graduate
      if( ($student->expectedDegreeType == 'BA') || ($student->expectedDegreeType == 'BS') ||
          ($student->expectedDegreeType == 'BA13') || ($student->expectedDegreeType == 'BS13'))
      {
          if( ($date_degree_expected->year == 2020) && ($date_degree_expected->month == 8) )
          {
            return true;
          }
          else
          {
            return false;
          }
      }
      else
      {
        return false;
      }

    }
}
