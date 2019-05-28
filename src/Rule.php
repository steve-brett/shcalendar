<?php
declare(strict_types=1);

namespace SHCalendar;

class Rule
{
  public function readable(array $rule): string
  {
    if ($rule['BYDAY'] == '3SU')
    {
      return 'The third Sunday in May';
    }
    if ($rule['BYDAY'] == '2SU')
    {
      return 'The second Sunday in May';
    }

    return 'The first Sunday in May';
  }

}


?>
