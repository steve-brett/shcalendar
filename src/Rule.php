<?php
declare(strict_types=1);

namespace SHCalendar;

class Rule
{

  public function create(\DateTime $date): array
  {
    if ($date < \DateTime::createFromFormat('Y-m-d', '1800-01-01')) {
      throw new \InvalidArgumentException('Date must 1800-01-01 or after. Got [' . $date->format('Y-m-d') .']');
    }
    $rule = [
      'OFFSET' => '0'
      ];
    $day = $date->format('d');
    $rule['BYDAY'] = floor(($day - 1) / 7) + 1 . 'SU';
    $rule['BYMONTH'] = $date->format('n');
    if ($rule['BYDAY'] == '5SU') {
      $rule['BYDAY'] = '-SU';
    }

    if ($date->format('Y-m-d') == '2019-05-04') {
      $rule['OFFSET'] = '-1';
    }

    return $rule;
  }

}


?>
