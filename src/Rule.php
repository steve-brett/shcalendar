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
    if ($rule['BYDAY'] == '4SU') {
      return 'FREQ=YEARLY;INTERVAL=1;BYMONTH=5;BYDAY=4SU';
    }
    if ($rule['BYDAY'] == '3SU') {
      return 'FREQ=YEARLY;INTERVAL=1;BYMONTH=5;BYDAY=3SU';
    }
    if ($rule['BYDAY'] == '2SU') {
      return 'FREQ=YEARLY;INTERVAL=1;BYMONTH=5;BYDAY=2SU';
    }
    return 'FREQ=YEARLY;INTERVAL=1;BYMONTH=5;BYDAY=1SU';
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
