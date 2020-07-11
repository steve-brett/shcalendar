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
	 * The position of the first day of each month in a non-leap year
	 *
	 * @var array
	 */
	private static $first_of_month = array(
		1 => 1,
		2 => 32,
		3 => 60,
		4 => 91,
		5 => 121,
		6 => 152,
		7 => 182,
		8 => 213,
		9 => 244,
		10 => 274,
		11 => 305,
		12 => 335
	);

	/**
	 * Array of special day keys
	 * @see RuleCreator::calculateSpecial()
	 * 
	 * @var array
	 */
	private static $specials = array(
		'newYear' => 'New Year\'s Day',
		'palmSunday' => 'Palm Sunday',
	);

	private function year_day(int $year_day, int $k): int
	{
		$sum = $year_day + $k;
		if ($sum <= 0 ) {
			return $sum - 1;
		}
		return $sum;
	}

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

		if ($rule['OFFSET'] && $rule['OFFSET'] !== 0) {
			
			$offset = $rule['OFFSET'];

			if ($rule['BYMONTH'] > 2) {
				// Affected by leap year
				$by_month_day = 'BYMONTHDAY=';
				for ($k = 0 ; $k < 7; $k++) { 
					$by_month_day .= $this->year_day(1 + $offset, $k) . ','; 
				}
				$by_month_day = substr($by_month_day, 0, -1) . ';';
				$year_day_limit = 8;
			} else {
				// Unaffected by leap year
				$by_month_day = '';
				$year_day_limit = 7;
			}

			$year_day = $this::$first_of_month[$rule['BYMONTH']] + $offset;
			
			$by_year_day = 'BYYEARDAY=';
			for ($k = 0 ; $k < $year_day_limit; $k++) { 
				$by_year_day .= $this->year_day($year_day, $k) . ','; 
			}
			$by_year_day = substr($by_year_day, 0, -1);

			if ($offset < 0) {
				$by_day = array_reverse(array_keys($this::$week_day_abbrev))[abs($offset)];
			} else {
				$by_day = array_keys($this::$week_day_abbrev)[$offset - 1];
			}
			$by_day = 'BYDAY=' . $by_day . ';';

			return 'FREQ=YEARLY;INTERVAL=1;'. $by_day . $by_month_day . $by_year_day;
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

		if ( isset($rule['SPECIAL']) )
		{
			return $this::$specials[$rule['SPECIAL']];
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
		if (!isset($rule['OFFSET']) )
		{
			$rule['OFFSET'] = 0;
		}
		if ( is_int($rule['OFFSET']) == false )
		{
			throw new \InvalidArgumentException('OFFSET format incorrect. Got [' . $rule['OFFSET'] . ']');
		}
		if (abs($rule['OFFSET']) >= 7 )
		{
			throw new \InvalidArgumentException('OFFSET must be between -6 and 6. Got [' . $rule['OFFSET'] . ']');
		}

		if ( isset($rule['SPECIAL']) )
		{
			// if ( !array_key_exists( $rule['SPECIAL'], $this::$specials ) ) 
			// {
			// 	throw new \InvalidArgumentException('SPECIAL key not valid. Got [' . $rule['SPECIAL'] . ']');
			// }
			return $rule;
		} 

		if (!isset($rule['BYDAY']) )
		{
			throw new \InvalidArgumentException('BYDAY is required.');
		}
		if ( strlen($rule['BYDAY']) < 3 )
		{
			throw new \InvalidArgumentException('BYDAY format incorrect. Got [' . $rule['BYDAY'] . ']');
		}
		if (!isset($rule['BYMONTH']))
		{
			throw new \InvalidArgumentException('BYMONTH is required.');
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
		catch (\Exception $e)
		{
			throw $e;
		}

		return $rule;
	}

}
