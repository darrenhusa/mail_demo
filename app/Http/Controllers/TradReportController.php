<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Queries\TradFulltimeEnrolled;
// use App\Queries\TradFulltimeEnrolledWithAthleticStatus;

class TradReportController extends Controller
{
    public function index()
    {

        // get dataset #1 - trad, full-time enrolled (a or w student-status);
        $results = TradFulltimeEnrolled::get('20191');
        // dd($results);
        // tinker($results);

        // TODO - Add entry_type_alt field to ds1
        // first-time = AH, HS, GE
        // continuing = CS, RS
        // transfer = TR, T2, T4


        $all_count = $results->count();
        // dd($all_count);

        //TODO get dataset #2 - at-athlete data

        //TODO get dataset #3 - sr-athlete data

        //TODO add code to combine the three baseline datasets!!

        // tinker($results);
        dd($results);
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
