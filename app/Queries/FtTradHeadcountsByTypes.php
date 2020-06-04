<?php

namespace App\Queries;

// use App\Queries\AtAthletes;
// use App\Queries\SrAthletes;
// use App\Queries\TradFulltimeEnrolled;


class FtTradHeadcountsByTypes
{
    public static function get($term)
    {

      // $term = '20191';

      $results1 = TradFulltimeEnrolled::get($term);
      $results2 = AtAthletes::get($term);
      $results3 = SrAthletes::get($term);

      $temp = $results1->zip($results2, $results3);


      $students = collect([]);

      foreach($temp as $record)
      {
        $new_array = [];

        foreach($record as $row)
        {
          array_push($new_array, (array)$row);
        }
        // how to convert an array to an object!!!!
        //https://thewebtier.com/php/convert-array-object-php/
        $result = json_decode(json_encode(array_merge($new_array[0], $new_array[1], $new_array[2])));

        $students->push($result);
      }

      // dd($students);

      foreach($students as $student)
      {
        $student->EntryTypeAlt = self::build_entry_type_alt_field($student->ETYP_ID);
        $student->IsAthlete = self::build_is_athlete_field($student->ISATATHLETE, $student->ISSRATHLETE);
        $student->FullName = $student->LAST_NAME . ', ' . $student->FIRST_NAME;
      }

      // $results = $query->get();

      // dd($query->toSql());
      // dd($results);

        return $students;
        // return $results;
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
