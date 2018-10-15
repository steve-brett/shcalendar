<?php

include 'ukBankHols.php';
include 'handyFunctions.php';

/*  The main singing formula calculation function.
*   Outputs possible values as a 2D array.
*
*/

function createFormula($date) {

  $makeFormula = Array();

  $nextSunday = date('Y-m-d', strtotime('this sunday', strtotime($date)));
  $nthDay = date('j', strtotime($nextSunday));
  // calculate its position in the month, but add 1 because the count is from 0
  $nthDay = floor(($nthDay) / 7) + 1;
  // create array (reference-day = Sunday, nth reference, reference-date, day)
  $makeFormula[] = array(7, $nthDay, $nextSunday, date('N',strtotime($date)));



  // if not Sunday, then calculate nth day in the month, e.g. First Saturday
  if (date('N', strtotime($date)) != 7){
    $nthDay = date('j', strtotime($date));
    // calculate its position in the month, but add 1 because the count is from 0
    $nthDay = floor($nthDay / 7) + 1;
    // create array (reference-day, nth reference, since-date, day)
    $makeFormula[] = array(date('N', strtotime($date)), $nthDay, date('Y-m-d', strtotime($date)), date('N',strtotime($date)));
  }

  /*
  // special day within a week?
  $specialdays = calculateBankHolidays(date('Y', $date));
  $special = find_closest($array, $date);
  //calclulate difference from special date
  $offset = date('j', $date) - date('j', strtotime($special));
  // create array (reference-day, nth reference, since-date, day, offset)
  $makeFormula[] = array(0, 0, date('Y-m-d', $special[0]), date('N',$date), $offset);
*/

  return $makeFormula;
}

// Turn the array into text
function printFormula($formula) {

  global $weekdays;

  $refDay = $formula[0];
  $nthDay = $formula[1];
  $refMonth = $formula[2];
  $day = $formula[3];

  $output .= "The ";

  if ($refDay != $day) {
    $output .= "$weekdays[$day] before the ";
  }
  $output .= ordinal($nthDay)." $weekdays[$refDay] in ";
  $output .= date("F", strtotime($refMonth))."\n";

  return $output;
}


$weekdays = array(
    1 => 'Monday',
    2 => 'Tuesday',
    3 => 'Wednesday',
    4 => 'Thursday',
    5 => 'Friday',
    6 => 'Saturday',
    7 => 'Sunday'
);

?>
