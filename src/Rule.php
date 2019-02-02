<?php
declare(strict_types=1);

namespace SHCalendar;

class Rule
{

  public function create(object $date): array
  {
    $rule = [
      'BYMONTH' => '5',
      'OFFSET' => '0'
      ];
    $day = $date->format('d');
    $rule['BYDAY'] = floor(($day - 1) / 7) + 1 . 'SU';
    $rule['BYMONTH'] = $date->format('n');

    return $rule;
  }

}


?>
