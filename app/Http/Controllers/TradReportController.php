<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Queries\TradFulltimeEnrolled;
use App\Queries\AtAthletes;
use App\Queries\SrAthletes;

class TradReportController extends Controller
{
    public function index()
    {

        $term = '20191';

        // Get dataset #1 - trad, full-time enrolled (a or w student-status);
        $results1 = TradFulltimeEnrolled::get($term);
        // dd($results1);
        // tinker($results);

        // Get dataset #2 - at-athlete data
        $results2 = AtAthletes::get($term);
        // dd($results1, $results2);

        // Get dataset #3 - sr-athlete data
        $results3 = SrAthletes::get($term);
        // dd($results3);

        // combine the three baseline datasets!!
        $temp = $results1->zip($results2, $results3);
        // $results = $temp->toArray();
        // dd($temp);
        // dd($results->all());
        // dd($results->all()->toArray());

        // This works for a single item ==> Now need to iterate over ALL items!!!
        /////////////////////////////////////////////////////////////////////////
        // $first = $temp->first();
        // dd($first);

        // $new_array = [];
        //
        // foreach($first as $row)
        // {
        //   array_push($new_array, (array)$row);
        // }
        // // var_dump($first, $new_array);
        // $result = array_merge($new_array[0], $new_array[1], $new_array[2]);
        // dd($first, $result);

        $new_collection = collect([]);

        foreach($temp as $record)
        {
          $new_array = [];
          foreach($record as $row)
          {
            array_push($new_array, (array)$row);
          }
          // $result = array_merge($new_array[0], $new_array[1], $new_array[2]);
          // how to convert an array to an object!!!!
          //https://thewebtier.com/php/convert-array-object-php/
          $result = json_decode(json_encode(array_merge($new_array[0], $new_array[1], $new_array[2])));

          $new_collection->push($result);
        }

        $students = $new_collection;
        // dd($students);

        foreach($students as $student)
        {
          $student->EntryTypeAlt = $this->build_entry_type_alt_field($student->ETYP_ID);
          $student->IsAthlete = $this->build_is_athlete_field($student->ISATATHLETE, $student->ISSRATHLETE);
          $student->FullName = $student->LAST_NAME . ', ' . $student->FIRST_NAME;
        }

        $studentsCounts = $students;

        $students = $students
            ->sortBy('FullName')
            ->paginate(20);

        $htmlTableCounts = $this->build_html_table_counts($studentsCounts);
        dd($htmlTableCounts);
        // dd($numGrandTotal, $numCheck);
        // dd($numFirstTimeAthletes);
        // dd($numGrandTotal, $numFirstTimeAthletes, $students);
        // dd($numGrandTotal, $students);
        // dd($students);

        return view('trad_headcount.index', compact('students'));
    }

    private function build_html_table_counts($studentsCounts)
    {

      $numGrandTotal = $studentsCounts->count();

      $numFirstTimeAthletes = $this->get_count_components($studentsCounts, 'first-time', 1);
      $numFirstTimeNonAthletes = $this->get_count_components($studentsCounts, 'first-time', 0);

      $numFirsTimeTotal = $numFirstTimeAthletes + $numFirstTimeNonAthletes;

      $numTransferAthletes = $this->get_count_components($studentsCounts, 'transfer', 1);
      $numTransferNonAthletes = $this->get_count_components($studentsCounts, 'transfer', 0);

      $numTransferTotal = $numTransferAthletes + $numTransferNonAthletes;

      $numContinuingAthletes = $this->get_count_components($studentsCounts, 'continuing/returning', 1);
      $numContinuingNonAthletes = $this->get_count_components($studentsCounts, 'continuing/returning', 0);

      $numContinuingTotal = $numContinuingAthletes + $numContinuingNonAthletes;

      $numAthleteTotal = $numFirstTimeAthletes + $numTransferAthletes + $numContinuingAthletes;
      $numNonAthleteTotal = $numFirstTimeNonAthletes + $numTransferNonAthletes + $numContinuingNonAthletes;

      $results = ['a11' => $numFirstTimeAthletes, 'a12' => $numFirstTimeNonAthletes, 'a13' => $numFirsTimeTotal,
                  'a21' => $numTransferAthletes, 'a22' => $numTransferNonAthletes, 'a23' => $numTransferTotal,
                  'a31' => $numContinuingAthletes, 'a32' => $numContinuingNonAthletes, 'a33' => $numContinuingTotal,
                  'a41' => $numAthleteTotal, 'a42' => $numNonAthleteTotal, 'a43' => $numGrandTotal];

      return $results;
    }

    private function get_count_components($collection, $entry_type, $athlete_status)
    {
      return $collection
          ->where('EntryTypeAlt', $entry_type)
          ->where('IsAthlete', $athlete_status)
          ->count();
    }

    private function build_is_athlete_field($value1, $value2)
    {
      if($value1 > 0 || $value2 > 0)
      {
        $result = 1;
      }
      else
      {
        $result = 0;
      }
      return $result;
    }

    private function build_entry_type_alt_field($value)
    {
      if($value == 'AH' || $value == 'HS' || $value == 'GE')
      {
        $result = 'first-time';
      }
      elseif($value == 'TR' || $value == 'T2' || $value == 'T4')
      {
        $result = 'transfer';
      }
      elseif($value == 'CS' || $value == 'RS' || $value == 'U2')
      {
        $result = 'continuing/returning';
      }
      else
      {
        $result = 'other';
      }

      return $result;
    }


    public function get_trad_ft()
    {

        $results = TradFulltimeEnrolledWithAthleticStatus::get('20191');
        dd($results);
        // tinker($results);

        $all_count = $results->count();

        // filter for full-time
        $fulltime_count = $results
            ->filter(function ($item) {
                return $item['TU_CREDIT_ENRL'] >= 12;
              })
            ->count();

        $parttime_count = $results
            ->filter(function ($item) {
                return $item['TU_CREDIT_ENRL'] < 12;
              })
            ->count();

        $all_by_entry_type = $results
            ->groupBy('ETYP_ID');


          dd($all_by_entry_type);
          // dd($all_count, $fulltime_count, $parttime_count);
    }
}
