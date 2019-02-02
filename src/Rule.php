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
    if ($date->format('d') == '11') {
      $rule['BYDAY'] = '2SU';
    }
    if ($date->format('d') == '18') {
      $rule['BYDAY'] = '3SU';
    }
    if ($date->format('d') == '25') {
      $rule['BYDAY'] = '4SU';
    }
    return $rule;
  }

}


?>
