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
  $makeFormula[] = array(7, $nthDay, date('m', strtotime($nextSunday)), date('N',strtotime($date)));



  // if not Sunday, then calculate nth day in the month, e.g. First Saturday
  if (date('N', strtotime($date)) != 7){
    $nthDay = date('j', strtotime($date));
    // calculate its position in the month, but add 1 because the count is from 0
    $nthDay = floor($nthDay / 7) + 1;
    // create array (reference-day, nth reference, since-date, day)
    $makeFormula[] = array(date('N', strtotime($date)), $nthDay, date('m', strtotime($date)), date('N',strtotime($date)));
  }

  // last day of month
  if (date('m', strtotime($date)) != date('m', strtotime("$date + 1week")) ){
    $makeFormula[] = array(0,0,"last ".date('D', strtotime($date))." in ".date('F', strtotime($date)) );
  }

  // special day within a week?
  $specialdays = calculateBankHolidays(date('Y', strtotime($date)) );
  //date('Y', $date);
  $specialId = find_closest_col1($specialdays, $date);
  //calclulate difference from special date
  $offset = date('j', strtotime($date)) - date('j', strtotime($specialdays[$specialId][0]));
  //$offset = date('j', strtotime($date)).$specialdays[$special][0];

  // create array (reference-day, nth reference, since-date, day, offset)
  $makeFormula[] = array(0,0, $specialId, date('N',$date), $offset);


  return $makeFormula;
}

//$ordinal = new NumberFormatter('en_US', NumberFormatter::SPELLOUT);
//$ordinal->setTextAttribute(NumberFormatter::DEFAULT_RULESET, "%spellout-ordinal");


// Turn the array into text
function printFormula($formula) {

  //$ordinal = new NumberFormatter('en_US', NumberFormatter::SPELLOUT);
  //$ordinal->setTextAttribute(NumberFormatter::DEFAULT_RULESET, "%spellout-ordinal");
  //global $ordinal;
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
  $output .= date("F", strtotime("2000-$refMonth-01"))."\n";

  return $output;
}

/*
// Turn the formula array into a date for a given year
function printDate($formula,int $year) {

  //$ordinal = new NumberFormatter('en_US', NumberFormatter::SPELLOUT);
  //$ordinal->setTextAttribute(NumberFormatter::DEFAULT_RULESET, "%spellout-ordinal");

  global $weekdays;

  $refDay = $formula[0];
  $nthDay = $formula[1];
  $refMonth = $formula[2];
  $day = $formula[3];

  $output .= date("F", strtotime($refMonth)).;

  if ($refDay != $day) {
    $output .= "$weekdays[$day] before the ";
  }
  $output .= ordinal($nthDay)." $weekdays[$refDay] in ";
  $output .= date("F", strtotime($refMonth))."\n";

  return $output;
}

*/


$weekdays = array(
    1 => 'Monday',
    2 => 'Tuesday',
    3 => 'Wednesday',
    4 => 'Thursday',
    5 => 'Friday',
    6 => 'Saturday',
    7 => 'Sunday'
);

/*
*    EXAMPLE:
*
*/

header("Content-type: text/plain");

$testdate = '2014-11-26';
$testFormula = createFormula(date($testdate));
echo date("l jS F Y", strtotime($testdate))."\n\n";

var_dump($testFormula);

foreach ($testFormula as $k => $formulaOptions) {
    echo printFormula($formulaOptions)."\n\n";
}
?>
