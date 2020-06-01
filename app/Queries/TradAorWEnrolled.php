<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;

class TradAorWEnrolled
{
    public static function get($term)
    {
      $name = 'CCSJ_PROD.CCSJ_CO_V_NAME';
      $sr_term = 'CCSJ_PROD.SR_STUDENT_TERM';
      $sr_term_credits = 'CCSJ_PROD.SR_ST_TERM_CRED';

      $sr_athlete = 'CCSJ_PROD.SR_STUD_TERM_ACT';
      $at_athlete = 'CCSJ_PROD.AT_ADMI_ACTIV';

      // $term = '20191';

      $query = DB::connection('odbc')->table($sr_term)
          ->where($sr_term.'.TERM_ID', $term)
        	->whereIn($sr_term.'.STUD_STATUS', ['A', 'W'])
        	->where($sr_term.'.PRGM_ID1', 'like', 'TR%')
          ->join($name, $sr_term.'.NAME_ID', '=', $name.'.NAME_ID')
          ->join($sr_term_credits, function ($join) use ($sr_term, $sr_term_credits) {
        		$join->on($sr_term.'.NAME_ID', '=', $sr_term_credits.'.NAME_ID');
              $join->on($sr_term.'.TERM_ID', '=', $sr_term_credits.'.TERM_ID');
            })
            ->select($sr_term.'.TERM_ID',
               $name.'.DFLT_ID',
               $name.'.LAST_NAME',
               $name.'.FIRST_NAME',
               $sr_term.'.STUD_STATUS',
               $sr_term.'.ETYP_ID',
               $sr_term.'.PRGM_ID1',
      		     $sr_term.'.MAMI_ID_MJ1',
               $sr_term_credits.'.TU_CREDIT_ENRL')
          ->orderBy($name.'.LAST_NAME', 'asc')
          ->orderBy($name.'.FIRST_NAME', 'asc')
          ->get();

          // dd($query);

        return $query;

          // filter for full-time
          // $fulltime = $results
          //     ->get()
          //     ->filter(function ($item) {
          //         return $item['TU_CREDIT_ENRL'] >= 12;
          //       });

          // dd($results);
          // dd($results->get());
          // dd($fulltime);
          // dd($fulltime->count());
          // dd($results-get());

    }
}
