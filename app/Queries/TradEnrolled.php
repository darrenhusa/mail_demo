<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;

class TradEnrolled
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
         ->select($sr_term . '.TERM_ID',
                 $name . '.DFLT_ID',
                 $name . '.LAST_NAME',
                 $name . '.FIRST_NAME',
                 $sr_term . '.STUD_STATUS',
                 $sr_term . '.CDIV_ID',
                 $sr_term . '.ETYP_ID',
                 $sr_term . '.PRGM_ID1',
                 $sr_term . '.MAMI_ID_MJ1',
                 $sr_term_credits . '.TU_CREDIT_ENRL',
               );

      $students = $query1->get();

      $studentsWithNewFields = $students->map(function($student) {
          $student->FullName = self::build_full_name_field($student->LAST_NAME, $student->FIRST_NAME);
          $student->majorDesc = self::lookup_empower_major_description($student->MAMI_ID_MJ1);
          $student->FtPtStatus = self::build_ft_pt_undergraduate_status_field($student->TU_CREDIT_ENRL);
          $student->IsAOrWInNextTerm = self::build_is_a_or_w_status_in_term($student->DFLT_ID, '20201');
          $student->numSrSports = self::get_number_of_sr_sports($student->TERM_ID, $student->DFLT_ID);
          $student->IsSrAthlete = self::build_is_sr_athlete_field($student->numSrSports);
          $student->Teams = self::build_teams_field($student->TERM_ID, $student->DFLT_ID);
          return $student;
      });

      // dd($query1->toSql());
      // dd($query1->get());
      // dd($results1);

        return $studentsWithNewFields;

    }


    private static function build_full_name_field($last, $first)
    {
        return  $last . ', ' . $first;
    }


    // this function is duplicated!!!
    // TODO - refactor code into an Empower helper utility class file???
    private static function get_number_of_sr_sports($term, $studentId)
    {
      return \DB::connection('odbc')
          ->table('CCSJ_PROD.SR_STUD_TERM_ACT')
          ->select('TERM_ID', 'DFLT_ID', 'CCSJ_PROD.SR_STUD_TERM_ACT.ACTI_ID')
          ->join('CCSJ_PROD.CCSJ_CO_V_NAME', 'CCSJ_PROD.CCSJ_CO_V_NAME.NAME_ID', '=', 'CCSJ_PROD.SR_STUD_TERM_ACT.NAME_ID')
          ->join('CCSJ_PROD.CO_ACTIV_CODE', 'CCSJ_PROD.SR_STUD_TERM_ACT.ACTI_ID', '=', 'CCSJ_PROD.CO_ACTIV_CODE.ACTI_ID')
          ->where('TERM_ID', '=', $term)
          ->where('DFLT_ID', '=', $studentId)
          ->where('ATHLETIC_FLAG', '=', 'T')
          ->count();
    }


    private static function build_teams_field($term, $studentId)
    {
      return \DB::connection('odbc')
          ->table('CCSJ_PROD.SR_STUD_TERM_ACT')
          ->select('TERM_ID', 'DFLT_ID', 'CCSJ_PROD.SR_STUD_TERM_ACT.ACTI_ID')
          ->join('CCSJ_PROD.CCSJ_CO_V_NAME', 'CCSJ_PROD.CCSJ_CO_V_NAME.NAME_ID', '=', 'CCSJ_PROD.SR_STUD_TERM_ACT.NAME_ID')
          ->join('CCSJ_PROD.CO_ACTIV_CODE', 'CCSJ_PROD.SR_STUD_TERM_ACT.ACTI_ID', '=', 'CCSJ_PROD.CO_ACTIV_CODE.ACTI_ID')
          ->where('TERM_ID', '=', $term)
          ->where('DFLT_ID', '=', $studentId)
          ->where('ATHLETIC_FLAG', '=', 'T')
          ->orderBy('CCSJ_PROD.SR_STUD_TERM_ACT.ACTI_ID', 'asc')
          ->get()
          ->pluck('ACTI_ID')
          ->implode(' ');
    }


    private static function build_is_a_or_w_status_in_term($studentId, $term)
    {
      return \DB::connection('odbc')
          ->table('CCSJ_PROD.SR_STUDENT_TERM')
          // ->select('TERM_ID', 'DFLT_ID', 'LAST_NAME', 'FIRST_NAME', 'STUD_STATUS')
          ->select('STUD_STATUS')
          ->join('CCSJ_PROD.CCSJ_CO_V_NAME', 'CCSJ_PROD.CCSJ_CO_V_NAME.NAME_ID', '=', 'CCSJ_PROD.SR_STUDENT_TERM.NAME_ID')
          ->where('TERM_ID', '=', $term)
          ->whereIn('STUD_STATUS', ['A', 'W'])
          ->where('DFLT_ID', '=', $studentId)
          ->count();
    }


    private static function lookup_empower_major_description($major_code)
    {
      return \DB::connection('odbc')
          ->table('CCSJ_PROD.CO_MAJOR_MINOR')
          ->where('MAMI_ID', '=', $major_code)
          // ->value('DESCR')
          ->pluck('DESCR')
          ->first();
    }


    private static function build_is_sr_athlete_field($value)
    {
      return ($value > 0) ? true : false;
    }


    private static function build_ft_pt_undergraduate_status_field($value)
    {
      return ($value >= 12) ? 'FT' : 'PT';
    }

}
