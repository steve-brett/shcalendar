<?php
declare(strict_types=1);

namespace SHCalendar;

class Rule
{
  public function readable(array $rule): string
  {
    $dateObj   = \DateTime::createFromFormat('!m', sprintf("%02s", $rule['BYMONTH']) );
    $monthName = $dateObj->format('F');

    if ($rule['BYDAY'] == '-SU')
    {
      return 'The last Sunday in ' . $monthName;
    }
    $ordinal = substr($rule['BYDAY'], 0, 1);

    $formatter = new \NumberFormatter('en_US', \NumberFormatter::SPELLOUT);
    $formatter->setTextAttribute(\NumberFormatter::DEFAULT_RULESET,
                        "%spellout-ordinal");

    return 'The ' . $formatter->format($ordinal) . ' Sunday in ' . $monthName;
  }

}


?>
