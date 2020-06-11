<?php

namespace App\Helpers;

class EmpowerHelper
{

    public static function get_number_of_at_sports($term, $studentId)
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

    public static function get_number_of_sr_sports($term, $studentId)
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


    public static function build_teams_field($term, $studentId)
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


    public static function build_is_a_or_w_status_in_term($studentId, $term)
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


    public static function lookup_empower_major_description($major_code)
    {
      return \DB::connection('odbc')
          ->table('CCSJ_PROD.CO_MAJOR_MINOR')
          ->where('MAMI_ID', '=', $major_code)
          // ->value('DESCR')
          ->pluck('DESCR')
          ->first();
    }


    public static function build_full_name_field($last, $first)
    {
        return  $last . ', ' . $first;
    }


    public static function build_is_sr_athlete_field($value)
    {
      return ($value > 0) ? true : false;
    }


    public static function build_ft_pt_undergraduate_status_field($value)
    {
      return ($value >= 12) ? 'FT' : 'PT';
    }


    public static function build_entry_type_alt_field($value)
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
