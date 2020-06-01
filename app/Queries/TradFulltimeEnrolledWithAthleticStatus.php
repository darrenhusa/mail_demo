<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;

class TradFulltimeEnrolledWithAthleticStatus
{
    public static function get($term)
    {
      $sr_athlete = 'CCSJ_PROD.SR_STUD_TERM_ACT';
      // $at_athlete = 'CCSJ_PROD.AT_ADMI_ACTIV';

      $sr_term = 'CCSJ_PROD.SR_STUDENT_TERM';
      $sr_term_credits = 'CCSJ_PROD.SR_ST_TERM_CRED';
      $name = 'CCSJ_PROD.CCSJ_CO_V_NAME';

      // $fields1 = "{$sr_term}.TERM_ID, " .
      //            "{$name}.DFLT_ID, {$name}.LAST_NAME, {$name}.FIRST_NAME, " .
      //           "{$sr_term}.STUD_STATUS, {$sr_term}.ETYP_ID, " .
      //           "{$sr_term}.PRGM_ID1";
                // "{$sr_term_credits}.TU_CREDIT_ENRL";
                // "{$sr_athlete}.ACTI_ID"

      // $fields2 = "count({$sr_athlete}.ACTI_ID) as num_sr_sports";

      $query1 = DB::table($sr_term)
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
        // ->selectRaw('count('. $sr_athlete.'.ACTI_ID) as num_sr_sports')
        // ->groupBy("{$name}.DFLT_ID")
        ->orderBy($name . '.LAST_NAME', 'asc')
        ->orderBy($name . '.FIRST_NAME', 'asc');

      $results1 = $query1->get();

      // dd($fields);
      // $query1 = DB::table("$sr_term")
      //    ->where("$sr_term.TERM_ID", $term)
      //    ->whereIn("{$sr_term}.STUD_STATUS", ['A', 'W'])
      //    ->where("{$sr_term}.PRGM_ID1", 'like', 'TR%')
      //    ->join($name, "{$sr_term}.NAME_ID", '=', "{$name}.NAME_ID")
      // //    ->join($sr_term_credits, function ($join) use ($sr_term, $sr_term_credits) {
      // //        $join->on($sr_term.'.NAME_ID', '=', $sr_term_credits.'.NAME_ID');
      // //        $join->on($sr_term.'.TERM_ID', '=', $sr_term_credits.'.TERM_ID');
      // // })
      // // ->where($sr_term_credits.'.TU_CREDIT_ENRL', '>=', 12)
      // ->select($fields1)
      // ->orderBy("{$name}.LAST_NAME", 'asc')
      // ->orderBy("{$name}.FIRST_NAME", 'asc');

      // dd($query1->toSql());
      // dd($query1->get());


      // raw sql from Access query builder
      // $sql = <<<EOT
      // SELECT CCSJ_PROD.CCSJ_CO_V_NAME.DFLT_ID,
      // CCSJ_PROD.CCSJ_CO_V_NAME.LAST_NAME,
      // CCSJ_PROD.CCSJ_CO_V_NAME.FIRST_NAME,
      // CCSJ_PROD.SR_STUDENT_TERM.ETYP_ID,
      // Count(CCSJ_PROD.SR_STUD_TERM_ACT.ACTI_ID) AS NumSrSports
      // FROM ((CCSJ_PROD.CCSJ_CO_V_NAME INNER JOIN
      // CCSJ_PROD.SR_STUDENT_TERM ON
      // CCSJ_PROD.CCSJ_CO_V_NAME.NAME_ID =
      // CCSJ_PROD.SR_STUDENT_TERM.NAME_ID) INNER JOIN
      // CCSJ_PROD.SR_ST_TERM_CRED ON
      // (CCSJ_PROD.SR_STUDENT_TERM.TERM_ID =
      // CCSJ_PROD.SR_ST_TERM_CRED.TERM_ID) AND
      // (CCSJ_PROD.SR_STUDENT_TERM.NAME_ID =
      // CCSJ_PROD.SR_ST_TERM_CRED.NAME_ID)) LEFT JOIN
      // CCSJ_PROD.SR_STUD_TERM_ACT ON
      // (CCSJ_PROD.SR_STUDENT_TERM.TERM_ID =
      // CCSJ_PROD.SR_STUD_TERM_ACT.TERM_ID) AND
      // (CCSJ_PROD.SR_STUDENT_TERM.NAME_ID =
      // CCSJ_PROD.SR_STUD_TERM_ACT.NAME_ID)
      // WHERE (((CCSJ_PROD.SR_STUDENT_TERM.TERM_ID)='20191') AND
      // ((CCSJ_PROD.SR_STUDENT_TERM.STUD_STATUS)='A' Or
      // (CCSJ_PROD.SR_STUDENT_TERM.STUD_STATUS)='W') AND
      // ((CCSJ_PROD.SR_STUDENT_TERM.PRGM_ID1) Like 'TR*') AND
      // ((CCSJ_PROD.SR_ST_TERM_CRED.TU_CREDIT_ENRL)>=12))
      // GROUP BY CCSJ_PROD.CCSJ_CO_V_NAME.DFLT_ID,
      // CCSJ_PROD.CCSJ_CO_V_NAME.LAST_NAME,
      // CCSJ_PROD.CCSJ_CO_V_NAME.FIRST_NAME,
      // CCSJ_PROD.SR_STUDENT_TERM.ETYP_ID
      // ORDER BY CCSJ_PROD.CCSJ_CO_V_NAME.LAST_NAME,
      // CCSJ_PROD.CCSJ_CO_V_NAME.FIRST_NAME
      // EOT;

      // $test = DB::select(DB::raw($sql));

      // dd($test);

      // to create NumSrSports field
      $query2 = DB::connection('odbc')->table($sr_term)
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
      ->select($name. '.DFLT_ID')
      ->selectRaw('count('. $sr_athlete.'.ACTI_ID) as num_sr_sports')
      ->groupBy($name . '.DFLT_ID');

      // dd($query2->toSql());
      $results2 = $query2->get();
      // dd($query2->get());

      // dd($results1->toArray(), $results2->toArray());

      //TODO - Need to merge the two collections!!!!?????
      $merged = array_merge($results1->toArray(), $results2->toArray());
      // $merged = $results1->merge($results2);

      dd($merged);

        // build IsSrAthlete field from ACTI_ID
        // If ACTI_ID is not null then TRUE ELSE FALSE
        // $new = $query->map(function($item) {
        //   ($item['ACTI_ID']) ? ($item['IsSrAthlete'] = 1) : ($item['IsSrAthlete'] = 0);
        //   return $item;
        // });

        //long form
        // $new = $query->map(function($item) {
        //   if ($item['ACTI_ID'])
        //   {
        //     $item['IsSrAthlete'] = 1;
        //   }
        //   else
        //   {
        //     $item['IsSrAthlete'] = 0;
        //   }
        //   return $item;
        // });

        // build EntryTypeALt field from ETYP_ID
        // AH, HS = first-time
        // CS, RS = continuing or returning
        // TR, T2, T4 = transfer
        // others??

        // $new2 = $new->map(function($item) {
        //   if ($item['ETYP_ID'] == 'AH' || $item['ETYP_ID'] == 'HS' || $item['ETYP_ID'] == 'GE')
        //   {
        //     $item['EntryTypeALt'] = 'first-time';
        //   }
        //   elseif ($item['ETYP_ID'] == 'TR' || $item['ETYP_ID'] == 'T2' || $item['ETYP_ID'] == 'T4' || $item['ETYP_ID'] == 'U2')
        //   {
        //     $item['EntryTypeALt'] = 'transfer';
        //   }
        //   elseif ($item['ETYP_ID'] == 'CS' || $item['ETYP_ID'] == 'RS')
        //   {
        //     $item['EntryTypeALt'] = 'continuing/returning';
        //   }
        //   else
        //   {
        //     $item['EntryTypeALt'] = 'OTHER';
        //   }
        //   return $item;
        // });

        // return $new2;
        return $query;

    }
}
