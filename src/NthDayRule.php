<?php
declare(strict_types=1);

namespace SHCalendar;

class NthDayRule
{

  public function create(\DateTime $date): array
  {
    if ($date < \DateTime::createFromFormat('Y-m-d', '1800-01-01')) {
      throw new \InvalidArgumentException('Date must be 1800-01-01 or after. Got [' . $date->format('Y-m-d') .']');
    }

    $refDay = $date->format('d');
    $count = floor(($refDay - 1) / 7) + 1;

    if ($count == 5) {
      throw new \InvalidArgumentException('Date is 5th of type in month - not annual. Got [' . $date->format('Y-m-d') .']');
    }

    $day = strtoupper(substr($date->format('D'), 0, -1));

    $rule = ['OFFSET' => '0'];
    $rule['BYDAY'] = $count . $day;
    $rule['BYMONTH'] = $date->format('n');

    return $rule;
  }

}


?>
