<?php
declare(strict_types=1);

namespace SHCalendar;
include 'dateFormula.php';
use SHCalendar\singingFormula;
use SHCalendar\interpretFormula;

$start = isset($_GET['start']) ? $_GET['start'] : '';
  $end = isset($_GET['end']) ? $_GET['end'] : '';

// maybe pass values to validator function in a try/catch
// instead of doing it here
$start_object = \DateTime::createFromFormat(\DateTimeInterface::ATOM, $start);
  $end_object = \DateTime::createFromFormat(\DateTimeInterface::ATOM, $end);

if (($start_object && $end_object) !== false)
{
  $formulae = new singingFormula($start_object);
  $formulae = $formulae->createFormulae();
  echo $start_object->format('l jS F Y') . PHP_EOL;
  echo   $end_object->format('l jS F Y') . PHP_EOL;
  // send dates to function here
}
else
{
  echo 'Invalid date. ';
  echo 'Got START: ' . $start . ', END: ' . $end . PHP_EOL;
}

echo '<br><br><a href="http://shcalendar.localhost/src/calculator.php?start=2019-05-23T23%3A14%3A57%2B02%3A00">calculator.php?start=2019-05-23T23%3A14%3A57%2B02%3A00</a>';
