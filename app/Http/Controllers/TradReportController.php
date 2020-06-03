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

        // TODO - Add entry_type_alt field to ds1
        // first-time = AH, HS, GE
        // continuing = CS, RS
        // transfer = TR, T2, T4

        //FIX - need to fix code below --- receiving the error
        //ERROR: Cannot use object of type stdClass as array

        // $withEntryTypeAlt = $results1->map(function($result) {
        //     if($result['ETYP_ID'] == 'AH' || $result['ETYP_ID'] == 'HS' || $result['ETYP_ID'] == 'GE')
        //     {
        //       $result['EntryTypeAlt'] = 'first-time';
        //     }
        //     elseif($result['ETYP_ID'] == 'TR' || $result['ETYP_ID'] == 'T2' || $result['ETYP_ID'] == 'T4')
        //     {
        //       $result['EntryTypeAlt'] = 'transfer';
        //     }
        //     elseif($result['ETYP_ID'] == 'CS' || $result['ETYP_ID'] == 'RS' || $result['ETYP_ID'] == 'U2')
        //     {
        //       $result['EntryTypeAlt'] = 'continuing/returning';
        //     }
        //     else
        //     {
        //       $result['EntryTypeAlt'] = 'OTHER';
        //     }
        //     return $result['EntryTypeAlt'];
        // });
        // // dd($newItems->toArray());
        // dd($withEntryTypeAlt);

        // $all_count = $results1->count();
        // dd($all_count);

        // Get dataset #2 - at-athlete data
        $results2 = AtAthletes::get($term);
        // dd($results1, $results2);

        // Get dataset #3 - sr-athlete data
        $results3 = SrAthletes::get($term);
        // dd($results3);

        //TODO add code to combine the three baseline datasets!!
        $temp = $results1->zip($results2, $results3);
        // $results = $temp->toArray();
        // dd($results);
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

        //TODO - Need to sort the collection by last and then firstname!!!

        ///////////////////////////////
        //SORT NOT WORKING??????!!!!!
        ///////////////////////////////
        $stu_x = $new_collection;
        // $students = $stu_x->sortBy('LAST_NAME');
        // $students = $stu_x->sortBy('LAST_NAME', 'FIRST_NAME');
        // $students = $stu_x->sortBy(['LAST_NAME', 'FIRST_NAME']);
        // $students = $stu_x->sortBy('LAST_NAME')->sortBy('FIRST_NAME');
        $students = $stu_x->sortBy('FIRST_NAME')->sortBy('LAST_NAME');
        // $students = $stu_x->sortBy('LAST_NAME', 'FIRST_NAME');
        // $students = $stu_x->sortBy('LAST_NAME');

        // $students = $stu_x->orderBy('LAST_NAME', 'ASC')->orderBy('FIRST_NAME', 'ASC');
        // $students = $stu_x->orderBy('FIRST_NAME')->orderBy('LAST_NAME');

        // $students = $stu_x->sortBy('LAST_NAME')->sortBy('LAST_NAME');

        // dd($students);
        // dd($new_collection);
        // return $students;
        // return $new_collection;

        /////////////////////////////////////////////////////////////////////////////////
        //TODO - Need to add an EntryType ALt field and a IsAnAthlete field to the data!!!
        /////////////////////////////////////////////////////////////////////////////////
        // return $new_collection->toJson();

        ////////////////////////////////////////////////////////////////////////
        //FIX when try and return a collection to the view I get an error
        // Facade\Ignition\Exceptions\ViewException
        // Trying to get property 'DFLT_ID' of non-object (View: C:\Users\darrenh\laravel_code\empower\mail_demo\resources\views\trad_headcount\index.blade.php)
        ////////////////////////////////////////////////////////////////////////
        return view('trad_headcount.index', compact('students'));
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
