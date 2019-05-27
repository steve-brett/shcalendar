<?php
declare(strict_types=1);

namespace SHCalendar;

use SHCalendar\Rule;

class NthSundayRule implements Rule
{
  /*private array $dayFormats = [
    'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun',
    'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'
  ];*/

  public function create(\DateTime $date, string $refDay = 'Sun'): array
  {
    if ($date < \DateTime::createFromFormat('Y-m-d', '1800-01-01')) {
      throw new \InvalidArgumentException('Date must be 1800-01-01 or after. Got [' . $date->format('Y-m-d') .']');
    }

    if ( $refDay == 'Nonsense' )
    {
      throw new \InvalidArgumentException('Reference day must be valid. Got [' . $refDay .']');
    }

    $nextRefDay = clone $date;
    $nextRefDay->modify('this ' . $refDay);

    // Find position of refDay in month
    $count = floor(($nextRefDay->format('d') - 1) / 7) + 1;
    if ($count == 5) {
      throw new \InvalidArgumentException('Date is 5th of type in month - not annual. Got [' . $date->format('Y-m-d') .']');
    }

    $day = strtoupper(substr($nextRefDay->format('D'), 0, -1));

    $rule['BYDAY'] = $count . $day;
    $rule['BYMONTH'] = $nextRefDay->format('n');
    $rule['OFFSET'] = $nextRefDay->diff($date)->format('%R%a');

    return $rule;
  }

}


?>
