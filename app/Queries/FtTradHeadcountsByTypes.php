<?php

namespace App\Queries;

use EmpowerHelper;

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
          $student->numAtSports = EmpowerHelper::get_number_of_at_sports($student->TERM_ID, $student->DFLT_ID);
          $student->numSrSports = EmpowerHelper::get_number_of_sr_sports($student->TERM_ID, $student->DFLT_ID);
          $student->EntryTypeAlt = EmpowerHelper::build_entry_type_alt_field($student->ETYP_ID);
          $student->IsAthlete = self::build_is_athlete_field($student->numAtSports, $student->numSrSports);
          $student->FullName = EmpowerHelper::build_full_name_field($student->LAST_NAME, $student->FIRST_NAME);
          return $student;
      });

      // dd($studentsWithNewFields);

        return $studentsWithNewFields;
    }


    //long form
    // private static function build_is_athlete_field($value1, $value2)
    // {
    //   if($value1 > 0 || $value2 > 0)
    //   {
    //     $result = 1;
    //   }
    //   else
    //   {
    //     $result = 0;
    //   }
    //   return $result;
    // }

    //short form uses ternary operator
    private static function build_is_athlete_field($value1, $value2)
    {
        return ($value1 > 0 || $value2 > 0) ? 1 : 0;
    }



}
