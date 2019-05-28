<?php
declare(strict_types=1);

namespace SHCalendar;

class Rule
{
  public function readable(array $rule): string
  {
    $ordinal = substr($rule['BYDAY'], 0, 1);

    $formatter = new \NumberFormatter('en_US', \NumberFormatter::SPELLOUT);
    $formatter->setTextAttribute(\NumberFormatter::DEFAULT_RULESET,
                        "%spellout-ordinal");

    return 'The ' . $formatter->format($ordinal) . ' Sunday in May';
  }

}


?>
