<?php
declare(strict_types=1);

namespace SHCalendar;

class Rule
{

  public function create(\DateTime $date): array
  {
    $rule = [
      'OFFSET' => '0'
      ];
    $day = $date->format('d');
    $rule['BYDAY'] = floor(($day - 1) / 7) + 1 . 'SU';
    $rule['BYMONTH'] = $date->format('n');
    if ($rule['BYDAY'] == '5SU') {
      $rule['BYDAY'] = '-SU';
    }


    return $rule;
  }

}


?>
