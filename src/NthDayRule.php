<?php
declare(strict_types=1);

namespace SHCalendar;

use SHCalendar\Rule;

class NthDayRule implements Rule
{

  public function create(\DateTime $date): array
  {
    if ($date < \DateTime::createFromFormat('Y-m-d', '1800-01-01')) {
      throw new \InvalidArgumentException('Date must be 1800-01-01 or after. Got [' . $date->format('Y-m-d') .']');
    }
    $refDay = $date->format('d');

    $rule = ['OFFSET' => '0'];
    $rule['BYDAY'] = floor(($refDay - 1) / 7) + 1 . 'SA';
    $rule['BYMONTH'] = $date->format('n');

    return $rule;
  }

}


?>
