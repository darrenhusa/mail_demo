<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;

class AtAthletes
{
    public static function get($term)
    {
      $sr_term = 'CCSJ_PROD.SR_STUDENT_TERM';
      $sr_term_credits = 'CCSJ_PROD.SR_ST_TERM_CRED';
      $name = 'CCSJ_PROD.CCSJ_CO_V_NAME';
      $at_athlete = 'CCSJ_PROD.AT_ADMI_ACTIV';

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
            ->leftJoin($at_athlete, function ($join) use ($sr_term, $at_athlete) {
        		      $join->on($sr_term.'.NAME_ID', '=', $at_athlete.'.NAME_ID');
            })
            ->selectRaw($name.'.DFLT_ID, count('. $at_athlete . '.ACTI_ID) as IsAtAthlete')
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
