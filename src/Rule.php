<?php
declare(strict_types=1);

namespace SHCalendar;
use RRule\RRule;

class Rule
{
  /**
	 * Weekdays numbered from 1 (ISO-8601 or `date('N')`).
	 * Used internally but public if a reference list is needed.
	 *
	 * @todo should probably be protected, with a static getter instead
	 * to avoid unintended modification
	 *
	 * @var array The name as the key
	 */
	protected static $week_days = array(
		'MO' => 'Monday',
		'TU' => 'Tuesday',
		'WE' => 'Wednesday',
		'TH' => 'Thursday',
		'FR' => 'Friday',
		'SA' => 'Saturday',
		'SU' => 'Sunday'
  );

  public function readable(array $rule): string
  {
    $dateObj   = \DateTime::createFromFormat('!m', sprintf("%02s", $rule['BYMONTH']) );
    $monthName = $dateObj->format('F');

    $dayName = $this::$week_days[substr($rule['BYDAY'], 1, 2)];
   
    if ($rule['BYDAY'] == '-SU')
    {
      return 'The last ' . $dayName . ' in ' . $monthName;
    }
    $ordinal = substr($rule['BYDAY'], 0, 1);

    $formatter = new \NumberFormatter('en_US', \NumberFormatter::SPELLOUT);
    $formatter->setTextAttribute(\NumberFormatter::DEFAULT_RULESET, "%spellout-ordinal");

    return 'The ' . $formatter->format($ordinal) . ' ' . $dayName . ' in ' . $monthName;
  }

}


?>
