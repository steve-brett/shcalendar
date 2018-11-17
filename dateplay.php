<?php
header("Content-type: text/html");

include 'ukBankHols.php';
include 'handyFunctions.php';

require '../vendor/autoload.php';
use RRule\RRule;

/**
 * Creating date collection between two dates
 *
 * <code>
 * <?php
 * # Example 1
 * date_range("2014-01-01", "2014-01-20", "+1 day", "m/d/Y");
 *
 * # Example 2. you can use even time
 * date_range("01:00:00", "23:00:00", "+1 hour", "H:i:s");
 * </code>
 *
 * @author Ali OYGUR <alioygur@gmail.com>
 * @param string since any date, time or datetime format
 * @param string until any date, time or datetime format
 * @param string step
 * @param string date of output format
 * @return array
 */
function date_range($first, $last, $step = '+1 day', $output_format = 'Y-m-d')
{
    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);

    while ($current <= $last) {
        $dates[] = date($output_format, $current);
        $current = strtotime($step, $current);
    }

    return $dates;
}


// New year's day
$rrule = new RRule([
	'FREQ' => 'YEARLY',
	'INTERVAL' => 1,
	'DTSTART' => '2015-01-01',
	'COUNT' => 10
]);

foreach ( $rrule as $occurrence ) {
	echo $occurrence->format('D d M Y'),", ";
}

// Sat before first Sunday in July
$rrule = new RRule([
	'FREQ' => 'YEARLY',
	'INTERVAL' => 1,
	'DTSTART' => '2015-01-01',
	'COUNT' => 10
]);

foreach ( $rrule as $occurrence ) {
	echo $occurrence->format('D d M Y'),", ";
}


$testDate = new DateTime('2014-11-27', new DateTimeZone('UTC'));
$specialDays = calculateBankHolidays($testDate->format('Y'));
var_dump($specialDays);
$special = find_closest_col1($specialDays, $testDate->format('Y-m-d'));
echo $special . $specialDays[$special][0] . $specialDays[$special][1];

$testSundays = calculateFifthSundays(2018);
var_dump($testSundays);
$testSundays = calculateFifthSundays(2004);
var_dump($testSundays);
/*
// special day within a week?
$specialdays = calculateBankHolidays(date('Y', $date));
$special = find_closest($array, $date);
//calclulate difference from special date
$offset = date('j', $date) - date('j', strtotime($special));
// create array (reference-day, nth reference, since-date, day, offset)
$makeFormula[] = array(0, 0, date('Y-m-d', $special[0]), date('N',$date), $offset);
*/
