<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;

class SrAthletes
{
    public static function get($term)
    {
      $sr_term = 'CCSJ_PROD.SR_STUDENT_TERM';
      $sr_term_credits = 'CCSJ_PROD.SR_ST_TERM_CRED';
      $name = 'CCSJ_PROD.CCSJ_CO_V_NAME';
      $sr_athlete = 'CCSJ_PROD.SR_STUD_TERM_ACT';
      // $at_athlete = 'CCSJ_PROD.AT_ADMI_ACTIV';

      $query = DB::connection('odbc')->table($sr_term)
      	  ->where($sr_term.'.TERM_ID', $term)
        	->whereIn($sr_term.'.STUD_STATUS', ['A', 'W'])
        	->where($sr_term.'.PRGM_ID1', 'like', 'TR%')
      	   ->join($name, $sr_term.'.NAME_ID', '=', $name.'.NAME_ID')
      	    ->join($sr_term_credits, function ($join) use ($sr_term, $sr_term_credits) {
        		      $join->on($sr_term.'.NAME_ID', '=', $sr_term_credits.'.NAME_ID');
                  $join->on($sr_term.'.TERM_ID', '=', $sr_term_credits.'.TERM_ID');
            })
            ->where($sr_term_credits.'.TU_CREDIT_ENRL', '>=', 12)
            ->leftJoin($sr_athlete, function ($join) use ($sr_term, $sr_athlete) {
        		      $join->on($sr_term.'.NAME_ID', '=', $sr_athlete.'.NAME_ID');
                  $join->on($sr_term.'.TERM_ID', '=', $sr_athlete.'.TERM_ID');
            })
            ->selectRaw($name.'.DFLT_ID, count('. $sr_athlete . '.ACTI_ID) as IsSrAthlete')
            ->groupBy($name.'.DFLT_ID')
            ->orderBy($name . '.DFLT_ID', 'asc');
            // ->orderBy($name.'.LAST_NAME', 'asc')
            // ->orderBy($name.'.FIRST_NAME', 'asc');

      $results = $query->get();

      // dd($query->toSql());
      // dd($results);

        return $results;
    }
}
