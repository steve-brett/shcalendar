<?php
declare(strict_types=1);

namespace SHCalendar;

class Rule
{
  public function create(object $date): array
  {
    return ['BYMONTH' => '5',
      'BYDAY' => '2SU',
      'OFFSET' => '-1'
      ];
  }

}


?>
