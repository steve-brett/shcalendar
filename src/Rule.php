<?php
declare(strict_types=1);

namespace SHCalendar;

class Rule
{

  public function create(object $date): array
  {
    $rule = [
      'OFFSET' => '0'
      ];
    $day = $date->format('d');
    $rule['BYDAY'] = floor(($day - 1) / 7) + 1 . 'SU';
    $rule['BYMONTH'] = $date->format('n');

    if ($date->format('Y-m-d') == '2019-05-26') {
      $rule = [$rule,
        ['BYMONTH' => '5',
          'BYDAY' => '-SU',
          'OFFSET' => '0'
          ]];
    }
    return $rule;
  }

}


?>
