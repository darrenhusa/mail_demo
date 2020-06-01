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
        dd($results1);
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
        // dd($results2);

        // Get dataset #3 - sr-athlete data
        $results3 = SrAthletes::get($term);
        // dd($results3);

        //TODO add code to combine the three baseline datasets!!
        $temp = $results1->zip($results2, $results3);
        $results = $temp->toArray();
        dd($results);
        // dd($results->all());
        // dd($results->all()->toArray());

        ////////////////////////////////////////////////////////////////////
        //FIX code here!!!!!!!
        ////////////////////////////////////////////////////////////////////
        // $newItems = collect($results)->map(function($result) {
        //   // return $result->merge($result[0], $result[1], $result[2]);
        //   return array_merge($result[0], $result[1], $result[2]);
        // });
        // // dd($newItems->toArray());
        // dd($newItems);

        // $newItems = collect($newCollection->all());

        // $Athletes = $newItems->filter(function($item) { 	return $item['NumAtSports'] > 0 || $item['NumSrSports'] > 0;
        // });
        //
        // $NonAthletes = $newItems->filter(function($item) { 	return $item['NumAtSports'] == 0 && $item['NumSrSports'] == 0;
        // });


        // tinker($results);
        // dd($results);
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
