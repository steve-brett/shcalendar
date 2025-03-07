<?php
declare(strict_types=1);

namespace SacredHarpCalendar;

use RRule\RRule;
use RRule\RSet;

/**
 * Class to manage a given recurrence rule
 * @since 1.0.0
 */
class Rule
{
    /**
     * Weekdays with RFC5545 abbreviation as key
     *
     * @since 1.0.0
     */
    const WEEK_DAY_ABBREV = array(
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
     *
     * @since 1.0.0
     */
    const WEEK_DAYS = array(
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
     * @since 1.0.0
     */
    const FIRST_OF_MONTH = array(
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
     * @since 1.0.0
     */
    const SPECIALS = array(
        'newYear' => 'New Year\'s Day',
        'palmSunday' => 'Palm Sunday',
        'easter' => 'Easter',
        'mayDay' => 'May Day bank holiday',
        'whitsun' => 'the Whitsun bank holiday',
        'independence' => 'Independence Day',
        'southWestWhitsun' => 'the second Saturday after the Whitsun bank holiday',
        'summer' => 'the summer bank holiday',
        'scottishShenandoah' => 'the Saturday after the third Friday in October',
        'londonChristianHarmony' => 'the Sunday after the last Saturday in October',
        'thanksgiving' => 'Thanksgiving',
        'christmas' => 'Christmas Day',
        'boxingDay' => 'Boxing Day',
        'boxingDay+1' => 'the day after Boxing Day',
        // Fifth Sundays
        '5SUSpring' => 'the first fifth Sunday in the spring',
        '5SU47' => 'the first fifth Sunday after the 4th July',
        'L5SUT' => 'the last fifth Sunday before Thanksgiving',
        '5SULabour' => 'the first fifth Sunday after Labour Day',
        'L5SU' => 'the last fifth Sunday in the year',
    );

    /**
     * Array of dates for Easter for the nineteen-year Metonic cycle.
     * Array key is the golden number: Y mod 19 + 1.
     * Valid for 1900 <= Y < 2200.
     *
     * @see sappjw/calendars
     *
     * @since 1.0.0
     */
    const METONIC_CYCLE = array(
        1 => array(
            'BYYEARDAY' => array(-261,-260,-259,-258,-257,-256,-255),
            'DTSTART' => '1900-04-15',
            'UNTIL' => '2185-04-17',
        ),
        2 => array(
            'BYYEARDAY' => array(-272,-271,-270,-269,-268,-267,-266),
            'DTSTART' => '1901-04-07',
            'UNTIL' => '2186-04-09',
        ),
        3 => array(
            'BYYEARDAY' => array(-283,-282,-281,-280,-279,-278,-277),
            'DTSTART' => '1902-03-30',
            'UNTIL' => '2187-03-25',
        ),
        4 => array(
            'BYYEARDAY' => array(-264,-263,-262,-261,-260,-259,-258),
            'DTSTART' => '1903-04-12',
            'UNTIL' => '2188-04-13',
        ),
        5 => array(
            'BYYEARDAY' => array(-275,-274,-273,-272,-271,-270,-269),
            'DTSTART' => '1904-04-03',
            'UNTIL' => '2189-04-05',
        ),
        6 => array(
            'BYYEARDAY' => array(-257,-256,-255,-254,-253,-252,-251),
            'DTSTART' => '1905-04-23',
            'UNTIL' => '2190-04-25',
        ),
        7 => array(
            'BYYEARDAY' => array(-267,-266,-265,-264,-263,-262,-261),
            'DTSTART' => '1906-04-15',
            'UNTIL' => '2191-04-10',
        ),
        8 => array(
            'BYYEARDAY' => array(-278,-277,-276,-275,-274,-273,-272),
            'DTSTART' => '1907-03-31',
            'UNTIL' => '2192-04-01',
        ),
        9 => array(
            'BYYEARDAY' => array(-259,-258,-257,-256,-255,-254,-253),
            'DTSTART' => '1908-04-19',
            'UNTIL' => '2193-04-21',
        ),
        10 => array(
            'BYYEARDAY' => array(-270,-269,-268,-267,-266,-265,-264),
            'DTSTART' => '1909-04-11',
            'UNTIL' => '2194-04-06',
        ),
        11 => array(
            'BYYEARDAY' => array(-281,-280,-279,-278,-277,-276,-275),
            'DTSTART' => '1910-03-27',
            'UNTIL' => '2195-03-29',
        ),
        12 => array(
            'BYYEARDAY' => array(-262,-261,-260,-259,-258,-257,-256),
            'DTSTART' => '1911-04-16',
            'UNTIL' => '2196-04-17',
        ),
        13 => array(
            'BYYEARDAY' => array(-273,-272,-271,-270,-269,-268,-267),
            'DTSTART' => '1912-04-07',
            'UNTIL' => '2197-04-09',
        ),
        14 => array(
            'BYYEARDAY' => array(-284,-283,-282,-281,-280,-279,-278),
            'DTSTART' => '1913-03-23',
            'UNTIL' => '2198-03-25',
        ),
        15 => array(
            'BYYEARDAY' => array(-265,-264,-263,-262,-261,-260,-259),
            'DTSTART' => '1914-04-12',
            'UNTIL' => '2199-04-14',
        ),
        16 => array(
            'BYYEARDAY' => array(-276,-275,-274,-273,-272,-271,-270),
            'DTSTART' => '1915-04-04',
            'UNTIL' => '2181-04-01',
        ),
        17 => array(
            'BYYEARDAY' => array(-258,-257,-256,-255,-254,-253,-252),
            'DTSTART' => '1916-04-23',
            'UNTIL' => '2182-04-21',
        ),
        18 => array(
            'BYYEARDAY' => array(-268,-267,-266,-265,-264,-263,-262),
            'DTSTART' => '1917-04-08',
            'UNTIL' => '2183-04-13',
        ),
        19 => array(
            'BYYEARDAY' => array(-279,-278,-277,-276,-275,-274,-273),
            'DTSTART' => '1918-03-31',
            'UNTIL' => '2184-03-28',
        ),
    );

    /**
     * Array of special day rules
     * @see RuleCreator::calculateSpecial()
     *
     * @since 1.0.0
     */
    const SPECIAL_RULES = array(
        'newYear' => array(
            'rule' => 'BYMONTH=1;BYMONTHDAY=1',
            'byyearday' => 1,
            'category' => 'fixedDate'),

        'palmSunday' => array(
            'rule' => '',
            'byday' => 'SU',
            'category' => 'easter'),

        'easter' => array(
            'rule' => '',
            'byday' => 'SU',
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

        'southWestWhitsun' => array(
            'rule' => 'BYDAY=SA;BYYEARDAY=-209,-208,-207,-206,-205,-204,-203',
            'byyearday' => array(-209,-208,-207,-206,-205,-204,-203),
            'byday' => 'SA',
            'category' => 'fixedDay'),

        'independence' => array(
            'rule' => 'BYMONTH=7;BYMONTHDAY=4',
            'byyearday' => -181,
            'category' => 'fixedDate'),

        'summer' => array(
            'rule' => 'BYMONTH=8;BYDAY=-1MO',
            'byyearday' => array(-129,-128,-127,-126,-125,-124,-123),
            'byday' => 'MO',
            'category' => 'fixedDay'),

        'scottishShenandoah' => array(
            'rule' => 'BYDAY=SA;BYYEARDAY=-77,-76,-75,-74,-73,-72,-71',
            'byyearday' => array(-77,-76,-75,-74,-73,-72,-71),
            'byday' => 'SA',
            'category' => 'fixedDay'),

        'londonChristianHarmony' => array(
            'rule' => 'BYDAY=SU;BYYEARDAY=-67,-66,-65,-64,-63,-62,-61',
            'byyearday' => array(-67,-66,-65,-64,-63,-62,-61),
            'byday' => 'SU',
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

        'boxingDay+1' => array(
            'rule' => 'BYMONTH=12;BYMONTHDAY=27',
            'byyearday' => -5,
            'category' => 'fixedDate'),

        // Fifth Sundays
        '5SUSpring' => array(
            'rule' => 'BYDAY=SU;BYYEARDAY=-278,-277,-276,-247,-246,-217,-216',
            'byyearday' => array(-278,-277,-276,-247,-246,-217,-216),
            'byday' => 'SU',
            'category' => 'fixedDay'),

        '5SU47' => array(
            'rule' => 'BYDAY=SU;BYYEARDAY=-156,-155,-154,-125,-124,-123,-94',
            'byyearday' => array(-156,-155,-154,-125,-124,-123,-94),
            'byday' => 'SU',
            'category' => 'fixedDay'),

        'L5SUT' => array(
            'rule' => 'BYDAY=SU;BYYEARDAY=-124,-123,-94,-93,-64,-63,-62',
            'byyearday' => array(-124,-123,-94,-93,-64,-63,-62),
            'byday' => 'SU',
            'category' => 'fixedDay'),

        '5SULabour' => array(
            'rule' => 'BYDAY=SU;BYYEARDAY=-94,-93,-64,-63,-62,-33,-32',
            'byyearday' => array(-94,-93,-64,-63,-62,-33,-32),
            'byday' => 'SU',
            'category' => 'fixedDay'),

        'L5SU' => array(
            'rule' => 'BYDAY=SU;BYYEARDAY=-63,-62,-33,-32,-1,-2,-3',
            'byyearday' => array(-63,-62,-33,-32,-1,-2,-3),
            'byday' => 'SU',
            'category' => 'fixedDay'),
    );


    /**
     *
     * Output RFC5545 RRULE string
     *
     * @since 1.0.0
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

        if (!isset($rule['INTERVAL'])) {
            $rule['INTERVAL'] = 1;
        }

        $rule['INTERVAL'] = (int)$rule['INTERVAL'];

        if (isset($rule['SPECIAL'])) {
            if ('easter' === $rule['SPECIAL']
            || 'palmSunday' === $rule['SPECIAL']) {
                throw new \InvalidArgumentException('We are currently unable to calculate recurrence rules for Palm Sunday and Easter.');
            }

            return $this->rfc5545Special($rule);
        }

        if (!isset($rule['OFFSET'])) {
            return 'FREQ=YEARLY;INTERVAL=' . $rule['INTERVAL'] . ';BYMONTH='. $rule['BYMONTH'] . ';BYDAY=' . $rule['BYDAY'];
        }

        $month_week = (int) substr($rule['BYDAY'], 0, -2);

        $day = substr($rule['OFFSET'], -2);
        $offset = $this->calculateOffsetDays(substr($rule['BYDAY'], -2), $rule['OFFSET']);

        $year_day = $this::FIRST_OF_MONTH[$rule['BYMONTH']] + 7 * ($month_week -1);

        if ($month_week === -1) {
            /**
             * The start of the last week of each month is 7 days before
             * the first day of the next month.
             */
            $next_month = ($rule['BYMONTH'] + 1) % 12;
            $year_day = $this->yearDayAdder($this::FIRST_OF_MONTH[$next_month], -7);
        }

        $week = $this->createWeek($year_day);
        $year_days = $this->offsetByYearDay($week, $offset);

        return 'FREQ=YEARLY;INTERVAL=' . $rule['INTERVAL'] . ';BYDAY=' . $day . ';BYYEARDAY=' . $year_days ;
    }

    /**
     * Output sentence description of rule
     *
     * @since 1.0.0
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
        $startOffset = '';

        if (isset($rule['OFFSET'])) {
            return $this->readableOffset($rule);
        }

        if (isset($rule['SPECIAL'])) {
            if (isset($rule['STARTOFFSET'])) {
                if ($rule['STARTOFFSET'] == -1) {
                    return $this::SPECIALS[$rule['SPECIAL']] . ' and the day before';
                }
                $formatter = new \NumberFormatter('en_US', \NumberFormatter::SPELLOUT);
                $startoffset_count = $formatter->format(abs((int)$rule['STARTOFFSET']));

                return $this::SPECIALS[$rule['SPECIAL']] . ' and the ' . $startoffset_count . ' preceding days';
            }
            return ucfirst($this::SPECIALS[$rule['SPECIAL']]);
        }

        if (isset($rule['STARTOFFSET'])) {
            // Get reference day in ISO-8601 integer format
            $dayN = \RRule\RRule::WEEKDAYS[substr($rule['BYDAY'], -2)];

            // Add the offset to find the start date
            // We have to use pymod() as PHP's % returns negative
            $startOffsetDayN = \RRule\pymod($dayN + $rule['STARTOFFSET'], 7);
            $startOffsetDay = $this::WEEK_DAYS[$startOffsetDayN];

            if ($rule['STARTOFFSET'] < -1) {
                $joiner = ($rule['STARTOFFSET'] < -2) ? ' to ' : ' and ';

                // e.g. 'and the Thursday to Saturday before
                $startOffsetDay2N = \RRule\pymod($dayN - 1, 7);
                $startOffsetDay2 = $this::WEEK_DAYS[$startOffsetDay2N];

                $startOffsetDay .= $joiner . $startOffsetDay2;
            }
            $startOffset = ' and the ' . $startOffsetDay . ' before';
        }

        return ucfirst($this->readableStandard($rule)) . $startOffset ;
    }

    /**
     * Output sentence description of rule for offset
     *
     * @since 1.0.0
     * @param array $rule
     * @return string
     */
    private function readableOffset(array $rule) : string
    {
        $offset_sign = (int) substr($rule['OFFSET'], 0, -2);
        $modifier = ($offset_sign > 0) ? ' after ' : ' before ';

        $offset = 'The ' . $this::WEEK_DAY_ABBREV[substr($rule['OFFSET'], -2)] . $modifier;

        /**
         * Positive OFFSET combined with (always) negative STARTOFFSET
         */
        if ($offset_sign > 0 && isset($rule['STARTOFFSET'])) {
            $dayN = \RRule\RRule::WEEKDAYS[substr($rule['OFFSET'], -2)];

            // Add the offset to find the start date
            // We have to use pymod() as PHP's % returns negative
            $startOffsetDayN = \RRule\pymod($dayN + $rule['STARTOFFSET'], 7);
            $startOffsetDay = $this::WEEK_DAYS[$startOffsetDayN];

            if ($rule['STARTOFFSET'] < -1) {
                $joiner = ($rule['STARTOFFSET'] < -2) ? ' to ' : ' and ';

                // e.g. 'and the Thursday to Saturday before
                $startOffsetDay2N = \RRule\pymod($dayN - 1, 7);
                $startOffsetDay2 = $this::WEEK_DAYS[$startOffsetDay2N];

                $startOffsetDay .= $joiner . $startOffsetDay2;
            }

            $startOffset = ' and the ' . $startOffsetDay . ' before';

            if (isset($rule['SPECIAL'])) {
                return $offset . $this::SPECIALS[$rule['SPECIAL']] . $startOffset;
            }

            return $offset . $this->readableStandard($rule) . $startOffset;
        }

        /**
         * Negative OFFSET combined with (always) negative STARTOFFSET
         */
        if (isset($rule['STARTOFFSET'])) {
            $dayN = \RRule\RRule::WEEKDAYS[substr($rule['OFFSET'], -2)];

            // Add the offset to find the start date
            // We have to use pymod() as PHP's % returns negative
            $startOffsetDayN = \RRule\pymod($dayN + $rule['STARTOFFSET'], 7);
            $startOffsetDay = $this::WEEK_DAYS[$startOffsetDayN];

            $joiner = ($rule['STARTOFFSET'] < -1) ? ' to ' : ' and ';

            $offset = 'The ' . $startOffsetDay . $joiner . $this::WEEK_DAY_ABBREV[substr($rule['OFFSET'], -2)] . $modifier;

            if (isset($rule['SPECIAL'])) {
                return $offset . $this::SPECIALS[$rule['SPECIAL']];
            }

            return $offset . $this->readableStandard($rule);
        }

        if (isset($rule['SPECIAL'])) {
            return $offset . $this::SPECIALS[$rule['SPECIAL']];
        }

        return $offset . $this->readableStandard($rule);
    }

    /**
     * Readable output for nth Day rules.
     *
     * @since 1.0.0
     * @param array $rule
     * @return string
     */
    private function readableStandard(array $rule) : string
    {
        $dateObj   = \DateTime::createFromFormat('!m', sprintf("%02s", $rule['BYMONTH']));
        $monthName = $dateObj->format('F');

        $dayName = $this::WEEK_DAY_ABBREV[substr($rule['BYDAY'], -2)];

        $ordinal = substr($rule['BYDAY'], 0, -2);
        $formatter = new \NumberFormatter('en_US', \NumberFormatter::SPELLOUT);
        $formatter->setTextAttribute(\NumberFormatter::DEFAULT_RULESET, "%spellout-ordinal");
        $ordinal = $formatter->format((int)$ordinal);

        if (substr($rule['BYDAY'], 0, 1) == '-') {
            $ordinal = 'last';
        }

        return 'the '. $ordinal . ' ' . $dayName . ' in ' . $monthName;
    }

    /**
     * Get $count upcoming dates for $rule.
     * Returns end date only.
     *
     * @since 1.0.0
     * @param array $rule
     * @param integer $count <= 100
     * @param \DateTime|null $dtstart Start date, default now.
     * @return array|RRule
     */
    public function getEndDates(array $rule, int $count, ?\DateTime $dtstart = null)
    {
        if (
            $count < 1 ||
            $count > 100
        ) {
            throw new \InvalidArgumentException('$count must be between 1 and 100. Got [' . $count . ']');
        }

        if (!isset($rule['INTERVAL'])) {
            $rule['INTERVAL'] = 1;
        }

        $rule['INTERVAL'] = (int)$rule['INTERVAL'];

        if (
            isset($rule['SPECIAL']) &&
            ('easter' === $rule['SPECIAL'])
        ) {
            if (isset($rule['OFFSET'])) {
                $offset_n = $this->calculateOffsetDays('SU', $rule['OFFSET']);
                return $this->getEasterDateTimes($offset_n, $count, $dtstart, $rule['INTERVAL']);
            }
            return $this->getEasterDateTimes(0, $count, $dtstart, $rule['INTERVAL']);
        }

        if (
            isset($rule['SPECIAL']) &&
            ('palmSunday' === $rule['SPECIAL'])
        ) {
            if (isset($rule['OFFSET'])) {
                $offset_n = $this->calculateOffsetDays('SU', $rule['OFFSET']);
                return $this->getEasterDateTimes($offset_n - 7, $count, $dtstart, $rule['INTERVAL']);
            }
            return $this->getEasterDateTimes(-7, $count, $dtstart, $rule['INTERVAL']);
        }

        if ($dtstart) {
            try {
                return $dates = new \RRule\RRule($this->rfc5545($rule) . ';COUNT=' . $count, $dtstart);
            } catch (\Exception $e) {
                throw $e;
            }
        }

        try {
            $dates = new \RRule\RRule($this->rfc5545($rule) . ';COUNT=' . $count);
        } catch (\Exception $e) {
            throw $e;
        }

        return $dates;
    }

    /**
     * Get $count upcoming dates for $rule.
     *
     * Returned as array of DateTime objects with keys ['start'] and ['end'].
     *
     * @since 1.0.0
     * @param array $rule
     * @param integer $count <= 100
     * @param \DateTime|null $dtstart Start date, default now.
     * @return array
     */
    public function getDates(array $rule, int $count, ?\DateTime $dtstart = null) : array
    {
        $end_dates = $this->getEndDates($rule, $count, $dtstart);

        // Loop through all years
        foreach ($end_dates as $key => $end_date) {
            $dates[$key]['end'] = $end_date;
            $dates[$key]['start'] = clone $end_date;

            // If no startoffset, end is same as start
            if (!isset($rule['STARTOFFSET'])) {
                continue;
            }

            // Otherwise
            $dates[$key]['start']->modify($rule['STARTOFFSET'] . ' day');
        }

        return $dates;
    }

    /**
     * Get dates for $rule from $dtstart until $until.
     * Returns end date only.
     *
     * @since 1.0.0
     * @param array $rule
     * @param integer $count <= 100
     * @param \DateTime|null $dtstart Start date, default now.
     * @return array|RRule|null
     */
    public function getEndDatesUntil(array $rule, \DateTime $until, ?\DateTime $dtstart = null)
    {
        if (!isset($rule['INTERVAL'])) {
            $rule['INTERVAL'] = 1;
        }

        $rule['INTERVAL'] = (int)$rule['INTERVAL'];

        if (
            isset($rule['SPECIAL']) &&
            ('easter' === $rule['SPECIAL'])
        ) {
            if (isset($rule['OFFSET'])) {
                $offset_n = $this->calculateOffsetDays('SU', $rule['OFFSET']);
                return $this->getEasterDateTimeRange($offset_n, $until, $dtstart, $rule['INTERVAL']);
            }
            return $this->getEasterDateTimeRange(0, $until, $dtstart, $rule['INTERVAL']);
        }

        if (
            isset($rule['SPECIAL']) &&
            ('palmSunday' === $rule['SPECIAL'])
        ) {
            if (isset($rule['OFFSET'])) {
                $offset_n = $this->calculateOffsetDays('SU', $rule['OFFSET']);
                return $this->getEasterDateTimeRange($offset_n - 7, $until, $dtstart, $rule['INTERVAL']);
            }
            return $this->getEasterDateTimeRange(-7, $until, $dtstart, $rule['INTERVAL']);
        }

        if ($dtstart) {
            try {
                return $dates = new \RRule\RRule($this->rfc5545($rule) . ';UNTIL=' . $until->format('Ymd\THis\Z'), $dtstart);
            } catch (\Exception $e) {
                throw $e;
            }
        }

        try {
            $dates = new \RRule\RRule($this->rfc5545($rule) . ';UNTIL=' . $until->format('Ymd'));
        } catch (\Exception $e) {
            throw $e;
        }

        return $dates;
    }

    /**
     * Get dates for $rule from $dtstart until $until.
     *
     * @since 1.3.0
     * @param array $rule
     * @param \DateTime $until
     * @param \DateTime|null $dtstart
     * @return array
     */
    public function getDatesUntil(array $rule, \DateTime $until, ?\DateTime $dtstart = null) : array
    {
        $end_dates = $this->getEndDatesUntil($rule, $until, $dtstart);

        $dates = [];
        // Loop through all years
        foreach ($end_dates as $key => $end_date) {
            $dates[$key]['end'] = $end_date;
            $dates[$key]['start'] = clone $end_date;

            // If no startoffset, end is same as start
            if (!isset($rule['STARTOFFSET'])) {
                continue;
            }

            // Otherwise
            $dates[$key]['start']->modify($rule['STARTOFFSET'] . ' day');
        }

        return $dates;
    }
    /**
     * Returns RFC5545 valid recurrence rules for special dates
     *
     * @since 1.0.0
     * @param array $rule
     * @return string
     */
    private function rfc5545Special(array $rule): string
    {
        if (!array_key_exists($rule['SPECIAL'], $this::SPECIAL_RULES)) {
            throw new \InvalidArgumentException('Rule key does not exist. Got [' . $rule['SPECIAL'] . ']');
        }

        if (!isset($rule['OFFSET'])) {
            return 'FREQ=YEARLY;INTERVAL=' . $rule['INTERVAL'] . ';' . $this::SPECIAL_RULES[$rule['SPECIAL']]['rule'];
        }

        $day = substr($rule['OFFSET'], -2);

        // Category = fixedDay
        if ('fixedDay' === $this::SPECIAL_RULES[$rule['SPECIAL']]['category']) {
            $year_days = $this::SPECIAL_RULES[$rule['SPECIAL']]['byyearday'];
            $special_day = $this::SPECIAL_RULES[$rule['SPECIAL']]['byday'];
            $offset_n = $this->calculateOffsetDays($special_day, $rule['OFFSET']);
            $year_days = $this->offsetByYearDay($year_days, $offset_n);

            return 'FREQ=YEARLY;INTERVAL=' . $rule['INTERVAL'] . ';BYDAY=' . $day . ';BYYEARDAY=' . $year_days;
        }

        // Category = fixedDate
        $offset_sign = (int) substr($rule['OFFSET'], 0, -2);
        $year_day = $this::SPECIAL_RULES[$rule['SPECIAL']]['byyearday'];
        $year_days = $this->offsetByYearDayFixedDate($year_day, $offset_sign);

        return 'FREQ=YEARLY;INTERVAL=' . $rule['INTERVAL'] . ';BYDAY=' . $day . ';BYYEARDAY=' . $year_days;
    }

    /**
     * Calculate RFC5545 rules for Easter
     *
     * @since 1.0.0
     * @param array $rule
     * @param integer $offset
     * @return array
     */
    private function rfc5545Easter(int $offset = 0, int $count = 5) : array
    {
        $rset = new \RRule\RSet();

        $day = 'SU';

        // Otherwise calculate day from offset
        if ($offset !== 0) {
            $key = $offset % 7;
            $day = substr($this::WEEK_DAYS[$key], 0, 2);
        }

        foreach ($this::METONIC_CYCLE as $cycle) {
            $rset->addRRule(array(
                'FREQ' => 'YEARLY',
                'INTERVAL' => 19,
                'BYYEARDAY' => $this->offsetByYearDay($cycle['BYYEARDAY'], $offset),
                'BYDAY' => $day,
                'DTSTART' => date_create($cycle['DTSTART']),
                'UNTIL' => date_create($cycle['UNTIL']),
            ));
        }

        // Exclude dates up unto today
        $rset->addExRule(array(
            'FREQ' => 'YEARLY',
            'INTERVAL' => 1,
            'BYMONTH' => array(3,4,5),
            'BYMONTHDAY' => range(1, 31),
            'DTSTART' => date_create('1900-01-01'),
            'UNTIL' => date_create(),

        ));

        $output = [];
        foreach (range(0, $count-1) as $key) {
            $output[] = $rset[$key];
        }
        return $output;
    }

    /**
     * Get array of DateTimes relating to Easter
     *
     * @since 1.3.1
     * @param array $rule
     * @param integer $offset
     * @return array
     */
    private function getEasterDateTimeRange(int $offset, \DateTime $until, ?\DateTime $dtstart = null, int $interval = 1) : array
    {
        if (null == $dtstart) {
            $dtstart = new \DateTime('first day of January this year');
        }

        $latest = $dtstart;
        $output = [];

        $range = range($latest->format('Y'), $until->format('Y'));
        $time = $dtstart->format('H:i:s');

        foreach ($range as $key => $year) {
            // Skip non-interval years
            if ($key % $interval !== 0) {
                continue;
            }

            $easter = self::getEasterDateTime($year, $offset, $time);

            // Skip if easter date before dtstart
            if ($easter < $dtstart) {
                continue;
            }

            if ($easter <= $until) {
                $output[] = $easter;
            }
        }

        return $output;
    }

    /**
     * Get array of DateTimes relating to Easter
     *
     * @since 1.3.1
     * @param integer $offset
     * @param integer $count            How many dates to get
     * @param \DateTime|null $dtstart   Start date
     * @param integer $interval         Annual interval
     * @return array
     */
    private function getEasterDateTimes(int $offset, int $count, ?\DateTime $dtstart = null, int $interval = 1) : array
    {
        if (null == $dtstart) {
            $dtstart = new \DateTime('first day of January this year');
        }

        $output = [];
        $start = 1;
        $time = $dtstart->format('H:i:s');
        $year = (int)$dtstart->format('Y');

        $easter = self::getEasterDateTime($year, $offset, $time);
        if ($easter >= $dtstart) {
            $output[] = $easter;
            $count--;
        }

        // Bail if no more to calculate
        if ($count <= 0) {
            return $output;
        }

        $range = range($start, $count);

        foreach ($range as $increment) {
            $easter = self::getEasterDateTime($year + ($increment * $interval), $offset, $time);
            $output[] = $easter;
        }

        return $output;
    }

    /**
     * Get Easter DateTime avoiding C timezone errors
     *
     * @since 1.3.1
     * @see https://www.php.net/manual/en/function.easter-date.php#refsect1-function.easter-date-notes
     * @param integer $year
     * @return \DateTime
     */
    private static function getEasterDateTime(int $year, int $offset, string $time = '00:00:00') : \DateTime
    {
        $base = \DateTime::createFromFormat('Y-m-d H:i:s', "$year-03-21 $time");
        $days = easter_days($year) + $offset;

        if ($days < 0) {
            return $base->sub(new \DateInterval("P" . abs($days) . "D"));
        }

        return $base->add(new \DateInterval("P{$days}D"));
    }

    /**
     * Offset a BYYEARDAY sequence.
     *
     * @since 1.0.0
     * @param integer[] $days 	Array of BYYEARDAY values.
     * @param integer $offset	Offset amount <= +/-7.
     * @return string
     */
    private function offsetByYearDay(array $days, int $offset) : string
    {
        // Add offset to each day.
        foreach ($days as &$value) {
            $value = $this->yearDayAdder($value, $offset);
        }
        return implode(',', $days);
    }

    /**
     * Generate a week of year days.
     *
     * @param integer $year_day
     * @return array
     */
    private function createWeek(int $year_day) : array
    {
        $output = array();
        $limit = 7;

        for ($i = 0; $i<$limit; $i++) {
            $output[] = $this->yearDayAdder($year_day, $i);
        }

        return $output;
    }

    /**
     * Create a string of BYYEARDAY values for the week before or after $year_day.
     *
     * @since 1.0.0
     * @param integer $year_day
     * @param integer $offset
     * @return string
     */
    private function offsetByYearDayFixedDate(int $year_day, int $offset) : string
    {
        if (abs($offset) !== 1) {
            throw new \InvalidArgumentException('Offset should be 1 or -1. Got [' . $offset . ']');
        }

        $output = array();
        $limit = 7;

        for ($i = 1; $i<=$limit; $i++) {
            $output[] = $this->yearDayAdder($year_day, $i * $offset);
        }

        // Standardise order to largest absolute value first.
        if ($offset < 0) {
            $output = array_reverse($output);
        }
        return implode(',', $output);
    }

    /**
     * Add a number to a yearday value.
     * Takes the missing zero point into account.
     *
     * @since 1.0.0
     * @param integer $yearday
     * @param integer $offset
     * @return integer
     */
    private function yearDayAdder(int $yearday, int $offset) : int
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
     * @since 1.0.0
     * @param integer $a
     * @return integer
     */
    private function sign(int $a) : int
    {
        return ($a > 0) - ($a < 0);
    }

    /**
     * RRULE validator for Sacred Harp rules
     *
     * @since 1.0.0
     * @param array $rule
     * @return array $rule
     */
    private function validate(array $rule): array
    {
        if (isset($rule['OFFSET']) && !$this->validOffset($rule['OFFSET'])) {
            throw new \InvalidArgumentException('OFFSET format incorrect. Got [' . $rule['OFFSET'] . ']');
        }

        if (isset($rule['INTERVAL']) && !ctype_digit(strval($rule['INTERVAL']))) {
            throw new \InvalidArgumentException('Interval must be a whole number.');
        }

        if (isset($rule['SPECIAL'])) {
            if (!array_key_exists($rule['SPECIAL'], $this::SPECIALS)) {
                throw new \InvalidArgumentException('SPECIAL key not valid. Got [' . $rule['SPECIAL'] . ']');
            }
            return $rule;
        }

        if (!isset($rule['BYDAY'])) {
            throw new \InvalidArgumentException('BYDAY is required.');
        }
        if (strlen($rule['BYDAY']) < 3) {
            throw new \InvalidArgumentException('BYDAY format incorrect. Got [' . $rule['BYDAY'] . ']');
        }
        if (!isset($rule['BYMONTH'])) {
            throw new \InvalidArgumentException('BYMONTH is required.');
        }

        if (isset($rule['STARTOFFSET']) && !is_integer($rule['STARTOFFSET'])) {
            throw new \InvalidArgumentException('STARTOFFSET must be an integer. Got [' . $rule['STARTOFFSET'] . ']');
        }

        if (isset($rule['STARTOFFSET']) && $rule['STARTOFFSET'] > 0) {
            throw new \InvalidArgumentException('STARTOFFSET cannot be positive. Got [' . $rule['STARTOFFSET'] . ']');
        }

        if (isset($rule['STARTOFFSET']) && $rule['STARTOFFSET'] < -6) {
            throw new \InvalidArgumentException('STARTOFFSET must be between -1 and -6. Got [' . $rule['STARTOFFSET'] . ']');
        }

        // Validate using RRule
        try {
            new RRule([
                'FREQ' => 'YEARLY',
                'INTERVAL' => 1,
                'BYMONTH' => $rule['BYMONTH'],
                'BYDAY' => $rule['BYDAY'],
                'DTSTART' => '1800-01-01',
                'COUNT' => '2'
        ]);
        } catch (\Exception $e) {
            throw $e;
        }

        return $rule;
    }

    /**
     * Is 'OFFSET' valid?
     *
     * @since 1.0.0
     * @param mixed $offset
     * @return boolean
     */
    private function validOffset($offset) : bool
    {
        if (!is_string($offset)) {
            return false;
        }

        // String is 3-4 chars long
        if (strlen($offset) > 4 || strlen($offset) < 3) {
            return false;
        }

        // Last two chars are weekdays
        if (!array_key_exists(substr($offset, -2), $this::WEEK_DAY_ABBREV)) {
            return false;
        }

        // Prefix is 1 or -1
        if (abs((int) substr($offset, 0, -2)) !== 1) {
            return false;
        }

        return true;
    }

    /**
     * Calculate number of days between a day of the week and its offset.
     *
     * @example calculateOffsetDays('SU','-1SA') => -1
     *
     * @since 1.0.0
     * @param string $day MO,TU,WE,TH,FR,SA,SU
     * @param string $offset 1MO,1TU,... or -1MO,-1TU,...
     * @return integer
     */
    public function calculateOffsetDays(string $day, string $offset) : int
    {
        $day = \RRule\RRule::WEEKDAYS[$day];
        $offset_sign = (int) substr($offset, 0, -2);
        $offset_value = \RRule\RRule::WEEKDAYS[substr($offset, -2)];
        $offset = $offset_sign * $offset_value;

        if ($day === $offset_value) {
            return $offset_sign * 7;
        }

        // I can only explain this maths by diagram!
        if (($offset - $offset_sign * $day) < 0) {
            $offset += 7;
        }

        return ($offset_sign * $offset) - $day;
    }
}
