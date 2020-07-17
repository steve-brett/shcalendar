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
	 * The position of the first day of each month.
	 * Negative values from March account for both leap and non-leap years.
	 *
	 * @var array
	 */
	private static $first_of_month = array(
		1 => 1,
		2 => 32,
		3 => -306,
		4 => -275,
		5 => -245,
		6 => -214,
		7 => -184,
		8 => -153,
		9 => -122,
		10 => -92,
		11 => -61,
		12 => -31,
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
		'easter' => 'Easter',
		'mayDay' => 'May Day bank holiday',
		'whitsun' => 'the Whitsun bank holiday',
		'independence' => 'Independence Day',
		'5SU47' => 'the first fifth Sunday after the 4th July',
		'summer' => 'the summer bank holiday',
		'thanksgiving' => 'Thanksgiving',
		'christmas' => 'Christmas Day',
		'boxingDay' => 'Boxing Day',

	);

	/**
	 * Array of special day rules
	 * @see RuleCreator::calculateSpecial()
	 * 
	 * @var array
	 */
	private static $special_rules = array(
		'newYear' => array(
			'rule' => 'BYMONTH=1;BYMONTHDAY=1',
			'byyearday' => 1,
			'category' => 'fixedDate'),

		'palmSunday' => array(
			'rule' => '',
			'byyearday' => '',
			'category' => 'easter'),

		'easter' => array(
			'rule' => '',
			'byyearday' => '',
			'category' => 'easter'),

		'mayDay' => array(
			'rule' => 'BYMONTH=5;BYDAY=1MO',
			'byyearday' => array(-245,-244,-243,-242,-241,-240,-239),
			'byday' => 'MO',
			'category' => 'fixedDay'),

		'whitsun' => array(
			'rule' => 'BYMONTH=5;BYDAY=-1MO',
			'byyearday' => array(-221,-220,-219,-218,-217,-216,-215),
			'byday' => 'MO',
			'category' => 'fixedDay'),

		'independence' => array(
			'rule' => 'BYMONTH=7;BYMONTHDAY=4',
			'byyearday' => -181,
			'category' => 'fixedDate'),

		'5SU47' => array(
			'rule' => 'BYDAY=SU;BYYEARDAY=-156,-155,-154,-125,-124,-123,-94',
			'byyearday' => array(-156,-155,-154,-125,-124,-123,-94),
			'byday' => 'SU',
			'category' => 'fixedDay'),

		'summer' => array(
			'rule' => 'BYMONTH=8;BYDAY=-1MO',
			'byyearday' => array(-129,-128,-127,-126,-125,-124,-123),
			'byday' => 'MO',
			'category' => 'fixedDay'),

		'thanksgiving' => array(
			'rule' => 'BYMONTH=11;BYDAY=4TH',
			'byyearday' => array(-40,-39,-38,-37,-36,-35,-34),
			'byday' => 'TH',
			'category' => 'fixedDay'),

		'christmas' => array(
			'rule' => 'BYMONTH=12;BYMONTHDAY=25',
			'byyearday' => -7,
			'category' => 'fixedDate'),

		'boxingDay' => array(
			'rule' => 'BYMONTH=12;BYMONTHDAY=26',
			'byyearday' => -6,
			'category' => 'fixedDate'),

	);

	/**
	 * Addition function for days that span months or years.
	 * 
	 * Allows progression along a number line without zero:
	 * e.g 3,2,1,-1,-2,-3
	 * 
	 * TODO: rename
	 *
	 * @param integer $year_day
	 * @param integer $k
	 * @return integer
	 */
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

		if ( isset( $rule['SPECIAL'] ) )
		{
			if ('easter' === $rule['SPECIAL']
			|| 'palmSunday' === $rule['SPECIAL'])
			{
				throw new \InvalidArgumentException('palmSunday and easter cannot give reccurence rules yet.');
			}

			return $this->rfc5545_special($rule);
		}

		if ( !isset( $rule['OFFSET'] ) )
		{
			return 'FREQ=YEARLY;INTERVAL=1;BYMONTH='. $rule['BYMONTH'] . ';BYDAY=' . $rule['BYDAY'];
		}

		$day = substr($rule['OFFSET'], -2);
		$offset = $this->calculate_offset_days( substr($rule['BYDAY'], -2), $rule['OFFSET'] );

		$offset_sign = (int) substr($rule['OFFSET'], 0, -2);
		$year_day = $this::$first_of_month[$rule['BYMONTH']];
		$week = $this->create_week($year_day);
		$year_days = $this->offset_byyearday( $week, $offset );

		return 'FREQ=YEARLY;INTERVAL=1;BYDAY=' . $day . ';BYYEARDAY=' . $year_days;
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
		$offset = '';

		if ( isset( $rule['OFFSET'] ) )
		{
			$offset_sign = (int) substr($rule['OFFSET'], 0, -2);
			$modifier = ( $offset_sign > 0 ) ? ' after ' : ' before '; 

			$offset = 'The ' . $this::$week_day_abbrev[substr($rule['OFFSET'], -2)] . $modifier;
		}

		if ( isset($rule['SPECIAL']) )
		{
			return $offset . $this::$specials[$rule['SPECIAL']];
		}

		$dateObj   = \DateTime::createFromFormat('!m', sprintf("%02s", $rule['BYMONTH']) );
		$monthName = $dateObj->format('F');

		$dayName = $this::$week_day_abbrev[substr($rule['BYDAY'], -2)];

		$ordinal = substr($rule['BYDAY'], 0, -2);
		$formatter = new \NumberFormatter('en_US', \NumberFormatter::SPELLOUT);
		$formatter->setTextAttribute(\NumberFormatter::DEFAULT_RULESET, "%spellout-ordinal");
		$ordinal = $formatter->format($ordinal);

		if (substr($rule['BYDAY'], 0, 1) == '-')
		{
			$ordinal = 'last';
		}

		return ucfirst( $offset . 'the '. $ordinal . ' ' . $dayName . ' in ' . $monthName );
	}

	/**
	 * Returns RFC5545 valid reccurence rules for special dates
	 *
	 * @param array $rule
	 * @return string
	 */
	protected function rfc5545_special( array $rule ): string
	{
		if ( isset( $rule['OFFSET'] ) )
		{
			$day = substr($rule['OFFSET'], -2);

			if ( 'fixedDay' === $this::$special_rules[$rule['SPECIAL']]['category'] )
			{
				$year_days = $this::$special_rules[$rule['SPECIAL']]['byyearday'];
				$special_day = $this::$special_rules[$rule['SPECIAL']]['byday'];
				$offset_n = $this->calculate_offset_days( $special_day, $rule['OFFSET'] );
				$year_days = $this->offset_byyearday( $year_days, $offset_n );

            	return 'FREQ=YEARLY;INTERVAL=1;BYDAY=' . $day . ';BYYEARDAY=' . $year_days;
			}

			// Category = fixedDate
			$offset_sign = (int) substr($rule['OFFSET'], 0, -2);
			$year_day = $this::$special_rules[$rule['SPECIAL']]['byyearday'];
			$year_days = $this->offset_byyearday_fixed_date( $year_day, $offset_sign );

			return 'FREQ=YEARLY;INTERVAL=1;BYDAY=' . $day . ';BYYEARDAY=' . $year_days;
			
		}

		return 'FREQ=YEARLY;INTERVAL=1;' . $this::$special_rules[$rule['SPECIAL']]['rule'];
	}

	/**
	 * Offset a BYYEARDAY sequence.
	 *
	 * @param integer[] $days 	Array of BYYEARDAY values.
	 * @param integer $offset	Offset amount <= +/-7.
	 * @return string
	 */
	protected function offset_byyearday( array $days, int $offset ) : string
	{
		// Add offset to each day.
		foreach ($days as &$value) 
		{
			$value = $this->yearday_adder($value, $offset);
		}
		return implode(',', $days);
	}

	/**
	 * Generate a week of year days.
	 *
	 * @param integer $year_day
	 * @return array
	 */
	protected function create_week( int $year_day ) : array
	{
		$output = array();
		$limit = 7;

		for ($i = 0; $i<$limit; $i++) 
		{
			$output[] = $this->yearday_adder($year_day, $i);
		}

		return $output;
	}

	/**
	 * Undocumented function
	 *
	 * @param integer $year_day
	 * @param integer $offset
	 * @return string
	 */
	protected function offset_byyearday_fixed_date( int $year_day, int $offset ) : string
	{
		if (abs($offset) !== 1) {
			throw new \InvalidArgumentException('Offset should be 1 or -1. Got [' . $offset . ']');
		}
	
		$output = array();
		$limit = 7;

		for ($i = 1; $i<=$limit; $i++) 
		{
			$output[] = $this->yearday_adder($year_day, $i * $offset);
		}
	
		// Standardise order to largest absolute value first.
		if ( $offset < 0 ) 
		{
			$output = array_reverse($output);
		}
		return implode(',', $output);
	}

	/**
	 * Add a number to a yearday value. 
	 * Takes the missing zero point into account.
	 *
	 * @param integer $yearday
	 * @param integer $offset
	 * @return integer
	 */
	protected function yearday_adder(int $yearday, int $offset) : int
	{
		$yearday_sign = $this->sign($yearday);
		$offset_sign = $this->sign($offset);

		// If values are same sign, return sum
		if ($yearday_sign === $offset_sign) {
			return $yearday + $offset;
		}

		$sign_change = $this->sign($yearday + $offset) !== $yearday_sign;
		// If yearday doesn't change sign, return sum
		if (!$sign_change) {
			return $yearday + $offset;
		}
		
		// Otherwise offset the value
		return $yearday + $offset - $yearday_sign;
	}

	/**
	 * Return the sign of an integer
	 *
	 * @param integer $a
	 * @return integer
	 */
	protected function sign(int $a) : int
	{
		return ($a > 0) - ($a < 0);
	}

	/**
	 * RRULE validator for Sacred Harp rules
	 *
	 * @param array $rule
	 * @return array $rule
	 */
	protected function validate (array $rule): array
	{
		// if (!isset($rule['OFFSET']) )
		// {
		// 	$rule['OFFSET'] = 0;
		// }
		if ( isset( $rule['OFFSET'] ) && !$this->valid_offset( $rule['OFFSET'] ) )
		{
			throw new \InvalidArgumentException('OFFSET format incorrect. Got [' . $rule['OFFSET'] . ']');
		}

		if ( isset($rule['SPECIAL']) )
		{
			if ( !array_key_exists( $rule['SPECIAL'], $this::$specials ) ) 
			{
				throw new \InvalidArgumentException('SPECIAL key not valid. Got [' . $rule['SPECIAL'] . ']');
			}
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

	/**
	 * Is 'OFFSET' valid?
	 *
	 * @param mixed $offset
	 * @return boolean
	 */
	protected function valid_offset( $offset ) : bool
	{	
		if ( !is_string($offset) ) {
			return false;
		}

		// String is 3-4 chars long
		if ( strlen($offset) > 4 || strlen($offset) < 3 ) {
			return false;
		}

		// Last two chars are weekdays
		if ( !array_key_exists( substr($offset, -2), $this::$week_day_abbrev) )
		{
			return false;
		}

		// Prefix is 1 or -1
		if ( abs( (int) substr($offset, 0, -2) ) !== 1) {
			return false;
		}

		return true;
	}

	/**
	 * Calculate number of days between a day of the week and its offset.
	 * 
	 * @example calculate_offset('SU','-1SA') => -1
	 *
	 * @param string $day MO,TU,WE,TH,FR,SA,SU
	 * @param string $offset 1MO,1TU,... or -1MO,-1TU,...
	 * @return integer
	 */
	public function calculate_offset_days ( string $day, string $offset ) : int
	{
		$day = \RRule\RRule::$week_days[$day];
		$offset_sign = (int) substr($offset, 0, -2);
		$offset_value = \RRule\RRule::$week_days[substr($offset, -2)];
		$offset = $offset_sign * $offset_value;

		if ($day === $offset_value)
		{
			return $offset_sign * 7;
		}

		// I can only explain this maths by diagram!
		if ( ($offset - $offset_sign * $day) < 0 ) 
		{
			$offset += 7;		
		}

		return ($offset_sign * $offset) - $day;
	}

}
