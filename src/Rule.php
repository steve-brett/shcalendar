<?php
declare(strict_types=1);

namespace SHCalendar;

class Rule
{

  public function create(object $date): array
  {
    $rule = [
      'BYMONTH' => '5',
      'BYDAY' => '1SU',
      'OFFSET' => '-1'
      ];
    if ($date->format('Y-m-d') == '2019-05-11') {
      $rule['BYDAY'] = '2SU';
    }
    if ($date->format('Y-m-d') == '2019-05-18') {
      $rule['BYDAY'] = '3SU';
    }
    return $rule;
  }

}


?>
