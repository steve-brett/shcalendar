<?php
declare(strict_types=1);

namespace SHCalendar;

use SHCalendar\Rule;

class LastSundayRule implements Rule
{

  public function create(\DateTime $date): array
  {
    if ($date < \DateTime::createFromFormat('Y-m-d', '1800-01-01')) {
      throw new \InvalidArgumentException('Date must be 1800-01-01 or after. Got [' . $date->format('Y-m-d') .']');
    }

    $nextSunday = clone $date;
    $nextSunday->modify('this sunday');

    $monthCheck = clone $nextSunday;
    $monthCheck = $monthCheck->modify('+1 week');
    if ($nextSunday->format('m') == $monthCheck->format('m')) {
      throw new \InvalidArgumentException('Date is not last Sunday in month. Got [' . $date->format('Y-m-d') .']');    
    }

    $rule = ['BYDAY' => '-SU',
      'OFFSET' => '0'
    ];

    $rule['BYMONTH'] = $nextSunday->format('n');

    return $rule;
  }

}


?>
