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
      'OFFSET' => '0'
      ];
    if ($date->format('d') == '12') {
      $rule['BYDAY'] = '2SU';
    }
    if ($date->format('d') == '19') {
      $rule['BYDAY'] = '3SU';
    }
    if ($date->format('d') == '26') {
      $rule['BYDAY'] = '4SU';
    }
    return $rule;
  }

}


?>
