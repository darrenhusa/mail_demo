<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;

class TradFulltimeEnrolled
{
    public static function get($term)
    {
      $sr_term = 'CCSJ_PROD.SR_STUDENT_TERM';
      $sr_term_credits = 'CCSJ_PROD.SR_ST_TERM_CRED';
      $name = 'CCSJ_PROD.CCSJ_CO_V_NAME';

      $query1 = DB::connection('odbc')->table($sr_term)
         ->where($sr_term. '.TERM_ID', $term)
         ->whereIn($sr_term . '.STUD_STATUS', ['A', 'W'])
         ->where($sr_term . '.PRGM_ID1', 'like', 'TR%')
         ->join($name, $sr_term. '.NAME_ID', '=', $name . '.NAME_ID')
         ->join($sr_term_credits, function ($join) use ($sr_term, $sr_term_credits) {
              $join->on($sr_term.'.NAME_ID', '=', $sr_term_credits.'.NAME_ID');
              $join->on($sr_term.'.TERM_ID', '=', $sr_term_credits.'.TERM_ID');
         })
         ->where($sr_term_credits.'.TU_CREDIT_ENRL', '>=', 12)
         ->select($sr_term . '.TERM_ID',
                 $name . '.DFLT_ID',
                 $name . '.LAST_NAME',
                 $name . '.FIRST_NAME',
                 $sr_term . '.STUD_STATUS',
                 $sr_term . '.ETYP_ID',
                 $sr_term . '.PRGM_ID1')
          ->orderBy($name . '.DFLT_ID', 'asc');
          // ->orderBy($name . '.LAST_NAME', 'asc')
          // ->orderBy($name . '.FIRST_NAME', 'asc');

      $results1 = $query1->get();

      // dd($query1->toSql());
      // dd($query1->get());
      // dd($results1);

        return $results1;

    }
}
