<?php
header("Content-type: text/plain");

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
function date_range($first, $last, $step = '+1 day', $output_format = 'Y-m-d' ) {

    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);

    while( $current <= $last ) {

        $dates[] = date($output_format, $current);
        $current = strtotime($step, $current);
    }

    return $dates;
}
/*
$testweek = date_range("2018-06-27", "2018-07-04", "+1 day", "Y-m-d");
var_dump($testweek);
echo "\n\n";

foreach ($testweek as $date) {
    echo $date."\n";
    echo date('Y-m-d', strtotime("this sunday", strtotime($date)))."\n\n";
}
*/
echo date('Y-m-d', strtotime("fourth sat of october 2014"))."\n\n";

echo "Current PHP version: " . phpversion()."\n\n";

$playDate = new DateTime('fourth thu of october 2014');

echo $playDate->format('Y-m-d') . PHP_EOL;

$playDate->modify('this sat');

echo $playDate->format('Y-m-d') . PHP_EOL;

$testDate = new DateTime('2014-11-27', new DateTimeZone('UTC'));
//$testFormula = createFormula($testdate);
echo $testDate->format('l jS F Y') . PHP_EOL;

//$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
//echo $f->format(123456);
/*
$ordinal = new NumberFormatter('en_US', NumberFormatter::SPELLOUT);
$ordinal->setTextAttribute(NumberFormatter::DEFAULT_RULESET, "%spellout-ordinal");
echo $ordinal->format(1);
echo $ordinal->format(2);
echo $ordinal->format(3);
*/
?>
