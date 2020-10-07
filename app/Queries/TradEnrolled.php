<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;
use EmpowerHelper;

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
          $student->FullName = EmpowerHelper::build_full_name_field($student->LAST_NAME, $student->FIRST_NAME);
          $student->majorDesc = EmpowerHelper::lookup_empower_major_description($student->MAMI_ID_MJ1);
          $student->FtPtStatus = EmpowerHelper::build_ft_pt_undergraduate_status_field($student->TU_CREDIT_ENRL);
          $student->IsAOrWInNextTerm = EmpowerHelper::build_is_a_or_w_status_in_term($student->DFLT_ID, '20202');
          $student->numSrSports = EmpowerHelper::get_number_of_sr_sports($student->TERM_ID, $student->DFLT_ID);
          $student->IsSrAthlete = EmpowerHelper::build_is_sr_athlete_field($student->numSrSports);
          $student->Teams = EmpowerHelper::build_teams_field($student->TERM_ID, $student->DFLT_ID);
          $student->dateEarnedMostRecentBachelors = EmpowerHelper::date_earned_most_recent_bachelors($student->DFLT_ID);
          $student->expectedDateDegree = EmpowerHelper::lookup_empower_expected_date_degree($student->DFLT_ID);
          $student->expectedDegreeType = EmpowerHelper::lookup_empower_expected_degree_type($student->DFLT_ID);
          return $student;
      });

      $students = $studentsWithNewFields->sortBy('FullName');

      // $seniors = $studentsWithNewFields->filter(function($student) {
      //     return $student->CDIV_ID == 'SR';
      // });

      // dd($seniors->count(), $seniors);

      // $students = $studentsWithNewFields->orderBy('FullName');

      // dd($query1->toSql());
      // dd($query1->get());
      // dd($students);

        return $students;
    }

}
