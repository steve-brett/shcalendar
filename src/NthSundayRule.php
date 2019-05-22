<?php
declare(strict_types=1);

namespace SHCalendar;

use SHCalendar\Rule;

class NthSundayRule implements Rule
{

  public function create(\DateTime $date, string $refday = 'Sun'): array
  {
    if ($date < \DateTime::createFromFormat('Y-m-d', '1800-01-01')) {
      throw new \InvalidArgumentException('Date must be 1800-01-01 or after. Got [' . $date->format('Y-m-d') .']');
    }

    $rule = [
      'OFFSET' => 0
      ];
    $nextSunday = clone $date;
    $nextSunday->modify('this sun');
    $refDay = $nextSunday->format('d');

    $count = floor(($refDay - 1) / 7) + 1;
    if ($count == 5) {
      throw new \InvalidArgumentException('Date is 5th Sunday in month - not annual. Got [' . $date->format('Y-m-d') .']');
    }

    $rule['BYDAY'] = $count . 'SU';
    $rule['BYMONTH'] = $nextSunday->format('n');

    if ($date->format('D') !== 'Sun') {
      $rule['OFFSET'] = $nextSunday->diff($date)->format('%R%a');
    }
    return $rule;
  }

}


?>
