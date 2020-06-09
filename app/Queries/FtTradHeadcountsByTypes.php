<?php

namespace App\Queries;


class FtTradHeadcountsByTypes
{
    public static function get($term)
    {
      //beyonce hampton - does not have any 20201 record!!!
      // $studentId = '100124780';
      // count = 0

      // Kevin McCune has TWO AT-sports! MBO and MBS!!!
      // $studentId = '100110307';
      // count = 2

      // Hannah Gentry has ONE AT-sport WSO!!!
      // $studentId = '100116464';
      // count = 1

      // Andres Gomez is a PB - this is a non-athlete sport!!!
      // Need to join the remaining table to FILTER out records like this one!

      $students = TradFulltimeEnrolled::get($term);
      // dd($results1);

      // Add New fields: NumAtSports, NumSrSports
      $studentsWithNewFields = $students->map(function($student) {
          $student->numAtSports = self::get_number_of_at_sports($student->TERM_ID, $student->DFLT_ID);
          $student->numSrSports = self::get_number_of_sr_sports($student->TERM_ID, $student->DFLT_ID);
          $student->EntryTypeAlt = self::build_entry_type_alt_field($student->ETYP_ID);
          $student->IsAthlete = self::build_is_athlete_field($student->numAtSports, $student->numSrSports);
          $student->FullName = $student->LAST_NAME . ', ' . $student->FIRST_NAME;
          return $student;
      });

      // dd($studentsWithNewFields);

        return $studentsWithNewFields;
    }

    private static function get_number_of_at_sports($term, $studentId)
    {
      return \DB::connection('odbc')
          ->table('CCSJ_PROD.AT_ADMI_TERM')
          ->select('TERM_ID', 'ADST_ID', 'ETYP_ID', 'DFLT_ID', 'LAST_NAME', 'FIRST_NAME', 'CCSJ_PROD.AT_ADMI_ACTIV.ACTI_ID', 'ATHLETIC_FLAG')
          ->where('TERM_ID', '=', $term)
          ->where('ATHLETIC_FLAG', '=', 'T')
          ->where('DFLT_ID', '=', $studentId)
          ->join('CCSJ_PROD.CCSJ_CO_V_NAME', 'CCSJ_PROD.CCSJ_CO_V_NAME.NAME_ID', '=', 'CCSJ_PROD.AT_ADMI_TERM.NAME_ID')
          ->join('CCSJ_PROD.AT_ADMI_ACTIV', 'CCSJ_PROD.AT_ADMI_TERM.NAME_ID', '=', 'CCSJ_PROD.AT_ADMI_ACTIV.NAME_ID')
          ->join('CCSJ_PROD.CO_ACTIV_CODE', 'CCSJ_PROD.AT_ADMI_ACTIV.ACTI_ID', '=', 'CCSJ_PROD.CO_ACTIV_CODE.ACTI_ID')
          ->count();
    }

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


    private static function build_is_athlete_field($value1, $value2)
    {
      if($value1 > 0 || $value2 > 0)
      {
        $result = 1;
      }
      else
      {
        $result = 0;
      }
      return $result;
    }

    private static function build_entry_type_alt_field($value)
    {
      if($value == 'AH' || $value == 'HS' || $value == 'GE')
      {
        $result = 'first-time';
      }
      elseif($value == 'TR' || $value == 'T2' || $value == 'T4')
      {
        $result = 'transfer';
      }
      elseif($value == 'CS' || $value == 'RS' || $value == 'U2')
      {
        $result = 'continuing/returning';
      }
      else
      {
        $result = 'other';
      }

      return $result;
    }

}
