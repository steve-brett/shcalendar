<?php
declare(strict_types=1);

namespace SHCalendar;
use RRule\RRule;
use phpDocumentor\Reflection\Types\Boolean;

class Rule
{
  /**
	 * Weekdays with RFC5545 abbreviation as key
	 *
	 * @var array 
	 */
	protected static $week_day_abbrev = array(
		'MO' => 'Monday',
		'TU' => 'Tuesday',
		'WE' => 'Wednesday',
		'TH' => 'Thursday',
		'FR' => 'Friday',
		'SA' => 'Saturday',
		'SU' => 'Sunday'
  );

  /**
	 * Weekdays numbered from 1 (ISO-8601 or `date('N')`).
	 * Used internally but public if a reference list is needed.
	 *
	 * @var array The name as the key
	 */
  protected static $week_days = array(
		1 => 'Monday',
		2 => 'Tuesday',
		3 => 'Wednesday',
		4 => 'Thursday',
		5 => 'Friday',
		6 => 'Saturday',
		0 => 'Sunday'
  );

  /**
   * 
   * Output RFC5545 RRULE string
   *
   * @param array $rule
   * @return string
   */
  public function rfc5545(array $rule): string
  {
    try {
      $rule = $this->validate($rule);
    } catch (\Exception $e) {
      throw $e;
    }
    if ($rule['OFFSET'] == -1) {
      if ($rule['BYMONTH'] == 12) {
        return 'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=334,335,336,337,338,339,340,341';
      }
      if ($rule['BYMONTH'] == 11) {
        return 'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=304,305,306,307,308,309,310,311';
      }
      if ($rule['BYMONTH'] == 10) {
        return 'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=273,274,275,276,277,278,279,280';
      }
      if ($rule['BYMONTH'] == 9) {
        return 'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=243,244,245,246,247,248,249,250';
      }
      if ($rule['BYMONTH'] == 8) {
        return 'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=212,213,214,215,216,217,218,219';
      }
      if ($rule['BYMONTH'] == 7) {
        return 'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=181,182,183,184,185,186,187,188';
      }
      if ($rule['BYMONTH'] == 6) {
        return 'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=151,152,153,154,155,156,157,158';
      }
      if ($rule['BYMONTH'] == 5) {
        return 'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=120,121,122,123,124,125,126,127';
      }
      if ($rule['BYMONTH'] == 4) {
        return 'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=90,91,92,93,94,95,96,97';
      }
      if ($rule['BYMONTH'] == 3) {
        return 'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=59,60,61,62,63,64,65,66';
      }
      if ($rule['BYMONTH'] == 2) {
        return 'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYYEARDAY=31,32,33,34,35,36,37';
      }
      return 'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYYEARDAY=-1,1,2,3,4,5,6';
    }
    return 'FREQ=YEARLY;INTERVAL=1;BYMONTH='. $rule['BYMONTH'] . ';BYDAY=' . $rule['BYDAY'];
  }
  
  /**
   * Ouput sentence description of rule
   *
   * @param array $rule 
   * @return string Description of rule
   */
  public function readable(array $rule): string
  {
    try {
      $rule = $this->validate($rule);
    } catch (\Exception $e) {
      throw $e;
    }

    $dateObj   = \DateTime::createFromFormat('!m', sprintf("%02s", $rule['BYMONTH']) );
    $monthName = $dateObj->format('F');
    $offset = '';

    $dayName = $this::$week_day_abbrev[substr($rule['BYDAY'], -2)];

     if ($rule['OFFSET'] !== 0)
    {
      $weekDay = $rule['OFFSET'] + \RRule\RRule::$week_days[substr($rule['BYDAY'], -2)];
      $weekDay = ($weekDay + 7) % 7;

      $offset = $this::$week_days[$weekDay] . ' before the ';
    }

    $ordinal = substr($rule['BYDAY'], 0, -2);
    $formatter = new \NumberFormatter('en_US', \NumberFormatter::SPELLOUT);
    $formatter->setTextAttribute(\NumberFormatter::DEFAULT_RULESET, "%spellout-ordinal");
    $ordinal = $formatter->format($ordinal);

    if (substr($rule['BYDAY'], 0, 1) == '-')
    {
      $ordinal = 'last';
    }

    return 'The ' . $offset . $ordinal . ' ' . $dayName . ' in ' . $monthName;
  }

  /**
   * RRULE validator for Sacred Harp rules
   *
   * @param array $rule
   * @return array $rule
   */
  protected function validate (array $rule): array
  {
    if (!isset($rule['BYMONTH']) )
    {
      throw new \InvalidArgumentException('BYMONTH is required.');
    }
    if (!isset($rule['BYDAY']) )
    {
      throw new \InvalidArgumentException('BYDAY is required.');
    }
    if ( strlen($rule['BYDAY']) < 3 )
    {
      throw new \InvalidArgumentException('BYDAY format incorrect. Got [' . $rule['BYDAY'] . ']');
    }
    
    // Validate using RRule
    try 
    {
      new RRule([
        'FREQ' => 'YEARLY',
        'INTERVAL' => 1,
        'BYMONTH' => $rule['BYMONTH'],
        'BYDAY' => $rule['BYDAY'],
        'DTSTART' => '1800-01-01',
        'COUNT' => '2'
    ]);
    }
    catch (Exception $e)
    {
      throw $e;
    }

    if (!isset($rule['OFFSET']) )
    {
      $rule['OFFSET'] = 0;
    }
    if ( is_int($rule['OFFSET']) == false )
    {
      throw new \InvalidArgumentException('OFFSET format incorrect. Got [' . $rule['OFFSET'] . ']');
    }
    if (abs($rule['OFFSET']) > 7 )
    {
      throw new \InvalidArgumentException('OFFSET must be between -7 and 7. Got [' . $rule['OFFSET'] . ']');
    }
    return $rule;
  }

}


?>
