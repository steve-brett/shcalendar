<?php

include 'ukBankHols.php';
include 'handyFunctions.php';

class singingFormula {
  /**
   * @var array
   */
  var $singingFormulae = array();
  /**
   * @var DateTime
   */
  public $date;

  public function __construct($dateInput) {
    $this->date = $dateInput;
  }

  public function get_date() {
    return $this->date;
  }

  public function nthSunday() {
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
    $this->singingFormulae[] = array("$nthDay Sunday of ".$date->format('F'), $adjust->format('%R%a'));
  }

  public function nthDay() {
    $date = $this->date;

    // if not Sunday...
    if ($date->format('N') == 7){
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

  public function lastSunday() {
    $date = $this->date;
    // Find the next Sunday (unless it's already a Sunday)
    $nextSunday = clone $date;
    $nextSunday->modify('this sunday');

    // check if reference Sunday is the last in the month; if not, return false
    $monthCheck = clone $nextSunday;
    $monthCheck = $monthCheck->modify('+1 week');
    if ($nextSunday->format('m') == $monthCheck->format('m')){
      return false;
    }

    //find difference between date and reference Sunday
    $adjust = $nextSunday->diff($date);

    // add to array (reference nth day of month, difference from reference)
    $this->singingFormulae[] = array("last Sunday of ".$date->format('F'), $adjust->format('%R%a'));
  }

  public function lastDay() {
    $date = $this->date;

    // check if reference day is the last of its type in the month; if not, return false
    $monthCheck = clone $date;
    $monthCheck = $monthCheck->modify('+1 week');
    if ($date->format('m') == $monthCheck->format('m')){
      return false;
    }

    // add to array (refere nce nth day of month)
    $this->singingFormulae[] = array("last " . $date->format('l') . " of ".$date->format('F'));
  }

  public function createFormulae() {
    // clear output array of previous values
    unset($this->singingFormulae);
    $this->singingFormulae = array();
    $this->nthSunday();
    $this->nthDay();
    $this->lastSunday();
    $this->lastDay();
    return $this->singingFormulae;
  }
}

class interpretFormula {
  /**
   * @var string
   */
  var $singingFormula = array();
  /**
   * @var DateTime
   */
  public $date;
  /**
   * @var int
   */
  public $year;

  /* multiple constructor from php.net  */
  function __construct()
  {
    $a = func_get_args();
    $i = func_num_args();
    if (method_exists($this,$f='__construct'.$i)) {
        call_user_func_array(array($this,$f),$a);
    }
  }

  function __construct1($formulaInput)
  {
    $this->singingFormula = $formulaInput;
  }

  function __construct2($formulaInput,$year)
  {
    $this->singingFormula = $formulaInput;
  }

  public function get_formula() {
    return $this->$singingFormula;
  }

  public function printDate()

}


// Turn the formula array into a date for a given year
//function printDate($formula, $year) {
function printDate($formula, $year = null) {

  // default to current year if none given
  if(is_null($year)) {
    $year = date('Y');
  }

  $refDay = $formula[0] . $year;
  $adjust= $formula[1];

  $output = new DateTime($refDay, new DateTimeZone('UTC'));
  $output->modify("$adjust days");

  return $output;
}

/*
*    EXAMPLE:
*
*/

header("Content-type: text/plain");

$testDate = new DateTime('2014-11-27', new DateTimeZone('UTC'));

$classTest = new singingFormula($testDate);
$classTestOut = $classTest->createFormulae();
var_dump($classTest);

$classTest->date->modify('-1 week');
$classTestOut = $classTest->createFormulae();
var_dump($classTest);
/*
$testFormula = createFormula($testDate);
echo 'Input: ' . $testDate->format('l jS F Y') . PHP_EOL;
var_dump($testFormula);
// test date output for 2014
foreach ($testFormula as $k => $formulaOptions) {
  $testOutput = printDate($formulaOptions, '2014');
  echo $testOutput->format('l jS F Y') . PHP_EOL;
}
// printDate should default to current year if none given
foreach ($testFormula as $k => $formulaOptions) {
  $testOutput = printDate($formulaOptions);
  echo $testOutput->format('l jS F Y') . PHP_EOL;
}
echo PHP_EOL;

// test modifier to date
$testDate->modify('last Friday');
echo 'Input: ' . $testDate->format('l jS F Y') . PHP_EOL;
$testFormula = createFormula($testDate);
var_dump($testFormula);

foreach ($testFormula as $k => $formulaOptions) {
  $testOutput = printDate($formulaOptions, '2014');
  echo $testOutput->format('l jS F Y') . PHP_EOL;
}
echo PHP_EOL;

// test modifier to date
$testDate->modify('-2 days');
echo 'Input: ' . $testDate->format('l jS F Y') . PHP_EOL;
$testFormula = createFormula($testDate);
var_dump($testFormula);

foreach ($testFormula as $k => $formulaOptions) {
  $testOutput = printDate($formulaOptions, '2014');
  echo $testOutput->format('l jS F Y') . PHP_EOL;
}
*/

?>
