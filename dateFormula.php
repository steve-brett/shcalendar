<?php

include 'ukBankHols.php';
include 'handyFunctions.php';

class singingFormula
{
    /**
     * @var array
     */
    public $singingFormulae = array();
    /**
     * @var DateTime
     */
    public $date;

    public function __construct($dateInput)
    {
        $this->date = $dateInput;
    }

    public function get_date()
    {
        return $this->date;
    }

    public function nthSunday()
    {
        $date = $this->date;
        // Find the next Sunday (unless it's already a Sunday)
        $nextSunday = clone $date;
        $nextSunday->modify('this sunday');

        // calculate its position in the month, but add 1 because the count is from 0
        $nthDay = $nextSunday->format('j');
        $nthDay = floor($nthDay / 7) + 1;
        $nthDay = numToOrdinalWord($nthDay);

        //find difference between date and reference Sunday
        $adjust = $nextSunday->diff($date);

        // add to array (reference nth day of month, difference from reference)
        $this->singingFormulae[] = array("$nthDay Sunday of ".$nextSunday->format('F'), intval($adjust->format('%R%a')));
    }

    public function nthDay()
    {
        $date = $this->date;

        // if not Sunday...
        if ($date->format('N') == 7) {
            return false;
        }

        // reference day is given day
        $nthDay = clone $date;

        // calculate its position in the month, but add 1 because the count is from 0
        $nthDay = $nthDay->format('j');
        $nthDay = floor($nthDay / 7) + 1;
        $nthDay = numToOrdinalWord($nthDay);

        // add to array (reference nth day of month, difference from reference)
        $this->singingFormulae[] = array("$nthDay " . $date->format('l') . " of " . $date->format('F'));
    }

    public function lastSunday()
    {
        $date = $this->date;
        // Find the next Sunday (unless it's already a Sunday)
        $nextSunday = clone $date;
        $nextSunday->modify('this sunday');

        // check if reference Sunday is the last in the month; if not, return false
        $monthCheck = clone $nextSunday;
        $monthCheck = $monthCheck->modify('+1 week');
        if ($nextSunday->format('m') == $monthCheck->format('m')) {
            return false;
        }

        //find difference between date and reference Sunday
        $adjust = $nextSunday->diff($date);

        // add to array (reference nth day of month, difference from reference)
        $this->singingFormulae[] = array("last Sunday of ".$date->format('F'), intval($adjust->format('%R%a')));
    }

    public function lastDay()
    {
        $date = $this->date;

        // check if reference day is the last of its type in the month; if not, return false
        $monthCheck = clone $date;
        $monthCheck = $monthCheck->modify('+1 week');
        if ($date->format('m') == $monthCheck->format('m')) {
            return false;
        }

        // add to array (refere nce nth day of month)
        $this->singingFormulae[] = array("last " . $date->format('l') . " of ".$date->format('F'));
    }

    public function specialDay()
    {
        $date = $this->date;

        $specialDays = calculateBankHolidays($date->format('Y'));
        $specialKey = find_closest_col1($specialDays, $date->format('Y-m-d'));

        $refDay = new DateTime($specialDays[$specialKey][0], new DateTimeZone('UTC'));
        // check if reference day is within a week of date
        $adjust = $refDay->diff($date);
        if ($adjust->format('%a') > 7) {
            return false;
        }

        // add to array (refere nce nth day of month)
        $this->singingFormulae[] = array('special' . $specialKey, intval($adjust->format('%R%a')));
    }

    public function createFormulae()
    {
        // clear output array of previous values
        unset($this->singingFormulae);
        $this->singingFormulae = array();
        $this->nthSunday();
        $this->nthDay();
        $this->lastSunday();
        $this->lastDay();
        $this->specialDay();
        return $this->singingFormulae;
    }
}

class interpretFormula
{
    /**
     * @var string
     */
    public $singingFormula = array();
    /**
     * @var DateTime
     */
    public $date;
    /**
     * @var int
     */
    public $year;

    /* multiple constructor from php.net  */
    public function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this, $f='__construct'.$i)) {
            call_user_func_array(array($this,$f), $a);
        }
    }

    public function __construct1($formulaInput)
    {
        $this->singingFormula = $formulaInput;
        // If no year specified, use current year
        $this->year = date('Y');
    }

    public function __construct2($formulaInput, $year)
    {
        $this->singingFormula = $formulaInput;
        $this->year = $year;
    }

    public function get_formula()
    {
        return $this->singingFormula;
    }

    public function get_year()
    {
        return $this->year;
    }

    public function text()
    {
        // Return formula array as a string

        $refDay = $this->singingFormula[0];
        $year = $this->year;
        $refDayText = $refDay;

        if (startsWith($refDay, 'special')) {
            $specialDays = calculateBankHolidays($year);
            $specialKey = str_replace('special', '', $refDay);
            $refDay = $specialDays[$specialKey][0];
            $refDay = substr($refDay, 4);
            $refDayText = $specialDays[$specialKey][1];
            $article = array('', '');
            $refDay = $year . $refDay;
        } else {
            $article = array('The ','the ');
            $refDay = $refDay . $year;
        }

        // If $adjust element of array is empty, give output
        if (empty($this->singingFormula[1])) {
            $refDayText = str_replace(" of ", " in ", $refDayText);
            return $article[0] . $refDayText . '.';
        } else {
            $adjust = $this->singingFormula[1];
        }

        $date = new DateTime($refDay, new DateTimeZone('UTC'));
        // Otherwise convert sign of $adjust into before/after
        $date->modify("$adjust days");
        if ($adjust > 0) {
            $direction = ' after ' . $article[1];
        } else {
            $direction = ' before ' . $article[1];
        }

        $adjust = $date->format('l');
        $refDayText = str_replace(" of ", " in ", $refDayText);
        return 'The ' . $adjust . $direction . $refDayText . '.';
    }

    public function date()
    {
        // Return DateTime object when given formula array

        $refDay = $this->singingFormula[0];
        $year = $this->year;

        if (startsWith($refDay, 'special')) {
            $specialDays = calculateBankHolidays($year);
            $specialKey = str_replace('special', '', $refDay);
            $refDay = $specialDays[$specialKey][0];
            $refDay = substr($refDay, 4);
            $refDay = $year . $refDay;
        } else {
            $refDay = $refDay . $year;
        }

        $this->date = new DateTime($refDay, new DateTimeZone('UTC'));

        // If $adjust element of array is empty, give output
        if (empty($this->singingFormula[1])) {
            return $this->date;
        } else {
            $adjust = $this->singingFormula[1];
        }

        // Otherwise adjust
        $this->date->modify("$adjust days");

        return $this->date;
    }
}

/*
*    EXAMPLE:
*


header("Content-type: text/plain");

$testDate = new DateTime('2014-11-27', new DateTimeZone('UTC'));

$classTest = new singingFormula($testDate);
$testFormula = $classTest->createFormulae();
var_dump($classTest);

foreach ($testFormula as $k => $formulaOptions) {
  $testOutput = new interpretFormula($formulaOptions, '2014');
  echo $testOutput->text() . PHP_EOL;
  echo $testOutput->date()->format('l jS F Y') . PHP_EOL;
}
echo PHP_EOL;

foreach ($testFormula as $k => $formulaOptions) {
  $testOutput = new interpretFormula($formulaOptions);
  echo $testOutput->text() . PHP_EOL;
  echo $testOutput->date()->format('l jS F Y') . PHP_EOL;
}

/*
$classTest->date->modify('-1 week');
$classTestOut = $classTest->createFormulae();
var_dump($classTest);
*/
