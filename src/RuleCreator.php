<?php

declare(strict_types=1);

namespace SacredHarpCalendar;

class RuleCreator
{
    /**
     * Possible day formats for $refDay
     *
     * @var array
     */
    private static $dayFormats = [
        'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun',
        'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'
    ];

    /**
     * Create array of possible rules from dates
     *
     * @param \DateTime $start
     * @param \DateTime|null $end
     * @return array of rrule arrays
     */
    public function create(\DateTime $start, ?\DateTime $end = null): array
    {
        $input = $this->span($start, $end);
        $date = $input['DATE'];

        $day = $date->format('N');

        try {
            $output['NTHSUN'] = $this->nthDay($date, 'Sun');
        } catch (\Exception $e) {
        }

        try {
            $output['LASTSUN'] = $this->lastDay($date, 'Sun');
        } catch (\Exception $e) {
        }

        if ($day != 7) {
            try {
                $output['NTHDAY'] = $this->nthDay($date);
            } catch (\Exception $e) {
            }

            try {
                $output['LASTDAY'] = $this->lastDay($date);
            } catch (\Exception $e) {
            }
        }

        try {
            $output['SPECIAL'] = $this->special($date);
        } catch (\Exception $e) {
        }


        // Add STARTOFFSET to each array
        if (isset($input['STARTOFFSET'])) {
            foreach ($output as &$rule) {
                $rule['STARTOFFSET'] = $input['STARTOFFSET'];
            }
            unset($rule);
        }

        return $output;
    }

    /**
     * Generate reference date from span of dates
     *
     * ['DATE'] DateTime reference date
     * ['STARTOFFSET'] int offset from ref date
     *
     * @param \DateTime $start
     * @param \DateTime|null $end
     * @return array
     */
    public function span(\DateTime $start, ?\DateTime $end = null): array
    {
        // Time will mess with our calculations - set to midnight
        $start->setTime(0, 0, 0);
        if ($end == null) {
            $output['DATE'] = $start;
            return $output;
        }
        $end->setTime(0, 0, 0);

        $diff = $end->diff($start)->format('%r%a');

        if (abs($diff) > 6) {
            throw new \InvalidArgumentException('Dates must not span more than a week.
      Got [' . $start->format('Y-m-d') . ', ' . $end->format('Y-m-d') . ']');
        }
        // Swap if end is before start
        if ($diff > 0) {
            $tmp = $start;
            $start = $end;
            $end = $tmp;

            $diff = -$diff;
        }

        if (abs($diff) > 0) {
            $output['STARTOFFSET'] = (int)$diff;
        }

        $output['DATE'] = $end;
        return $output;
    }

    /**
     * Generate rule based on nth day in month
     *
     *  TODO change $refday to format 'N'?
     *
     * @param \DateTime $date
     * @param string $refDay
     * @return array
     */
    public function nthDay(\DateTime $date, string $refDay = null): array
    {
        if ($date < \DateTime::createFromFormat('Y-m-d', '1800-01-01')) {
            throw new \InvalidArgumentException('Date must be 1800-01-01 or after.
      Got [' . $date->format('Y-m-d') . ']');
        }

        if (isset($refDay) && !in_array(strtolower($refDay), $this::$dayFormats)) {
            throw new \InvalidArgumentException('Reference day must be valid.
      Got [' . $refDay . ']');
        }

        // Find next instance of $refDay
        $nextRefDay = clone $date;
        if (isset($refDay)) {
            $nextRefDay->modify('this ' . $refDay);
        }

        // Find position of refDay in month
        $count = floor(($nextRefDay->format('d') - 1) / 7) + 1;
        if ($count == 5) {
            throw new \InvalidArgumentException('Date is 5th of type in month - not annual.
      Got [' . $date->format('Y-m-d') . ']');
        }

        $day = strtoupper(substr($nextRefDay->format('D'), 0, -1));
        $offset = $nextRefDay->diff($date)->format('%R%a');

        $rule['BYDAY'] = $count . $day;
        $rule['BYMONTH'] = (int)$nextRefDay->format('n');
        if (abs($offset) > 0) {
            $rule['OFFSET'] = '-1' . strtoupper(substr($date->format('D'), 0, -1));
        }

        return $rule;
    }

    /**
     * Generate rule based on last day in month
     *
     *  TODO change $refday to format 'N'?
     *
     * @param \DateTime $date
     * @param string $refDay
     * @return array
     */
    public function lastDay(\DateTime $date, string $refDay = null): array
    {
        if ($date < \DateTime::createFromFormat('Y-m-d', '1800-01-01')) {
            throw new \InvalidArgumentException('Date must be 1800-01-01 or after.
      Got [' . $date->format('Y-m-d') . ']');
        }

        if (isset($refDay) && !in_array(strtolower($refDay), $this::$dayFormats)) {
            throw new \InvalidArgumentException('Reference day must be valid. Got [' . $refDay . ']');
        }

        // Find next instance of $refDay
        $nextRefDay = clone $date;
        if (isset($refDay)) {
            $nextRefDay->modify('this ' . $refDay);
        }

        $monthCheck = clone $nextRefDay;
        $monthCheck = $monthCheck->modify('+1 week');
        if ($nextRefDay->format('m') == $monthCheck->format('m')) {
            throw new \InvalidArgumentException('Date is not last of its type in month.
      Got [' . $date->format('Y-m-d') . ']');
        }

        $day = strtoupper(substr($nextRefDay->format('D'), 0, -1));
        $offset = $nextRefDay->diff($date)->format('%R%a');

        $rule['BYDAY'] = '-1' . $day;
        $rule['BYMONTH'] = (int)$nextRefDay->format('n');
        if (abs($offset) > 0) {
            $rule['OFFSET'] = '-1' . strtoupper(substr($date->format('D'), 0, -1));
        }

        return $rule;
    }

    /**
     * Generate rule based on proximity to special days, e.g. a bank holiday or Easter
     *
     * @param \DateTime $date
     * @return array
     */
    public function special(\DateTime $date): array
    {
        if ($date < \DateTime::createFromFormat('Y-m-d', '1800-01-01')) {
            throw new \InvalidArgumentException('Date must be 1800-01-01 or after.
      Got [' . $date->format('Y-m-d') . ']');
        }

        $year = (int)$date->format('Y');
        $special = $this->calculateSpecial($year);
        $special = $this->ymdToDatetime($special);
        $closest = $this->findClosest($date, $special);
        $offset = $closest['offset'];

        $rule['SPECIAL'] = $closest['date'];
        if (abs($offset) > 7) {
            // TODO Is this the right thing to do, or return false?
            throw new \InvalidArgumentException('Not within a week of a special day.
      Got [' . $date->format('Y-m-d') . ']');
        }
        if (abs($offset) > 0) {
            $rule['OFFSET'] = $this->sign($offset) . strtoupper(substr($date->format('D'), 0, -1));
        }
        return $rule;
    }

    /**
     * Generates array of special days in a given year.
     * Based on public domain work of David Scourfield.
     * @see Rule::$specials for keys.
     *
     * @param integer $year
     * @return array
     */
    public function calculateSpecial(int $year = null): array
    {
        // default to current year if not set
        $year = $year ?: date('Y');

        $specials = array();

        // New year's day:
        $specials['newYear'] = "$year-01-01";

        // Palm Sunday:
        $specials['palmSunday'] = date("Y-m-d", strtotime("+" . (easter_days($year) - 7) . " days", strtotime("$year-03-21 00:00:00")));

        /*
      // Good friday:
      $specials['goodFriday'] = date("Y-m-d", strtotime("+".(easter_days($year) - 2)." days", strtotime("$year-03-21 00:00:00")));
      */

        // Easter:
        $specials['easter'] = date("Y-m-d", strtotime("+" . easter_days($year) . " days", strtotime("$year-03-21 00:00:00")));

        /*
      // Easter Monday:
      $specials['easterMonday'] = date("Y-m-d", strtotime("+".(easter_days($year) + 1)." days", strtotime("$year-03-21 00:00:00")));
      */

        // May Day:
        if ($year == 1995) {
            $specials['mayDay'] = "1995-05-08"; // VE day 50th anniversary year exception
        } elseif ($year == 2020) {
            $specials['mayDay'] = "2020-05-08"; // VE day 75th anniversary year exception
        } else {
            switch (date("w", strtotime("$year-05-01 00:00:00"))) {
                case 0:
                    $specials['mayDay'] = "$year-05-02";
                    break;
                case 1:
                    $specials['mayDay'] = "$year-05-01";
                    break;
                case 2:
                    $specials['mayDay'] = "$year-05-07";
                    break;
                case 3:
                    $specials['mayDay'] = "$year-05-06";
                    break;
                case 4:
                    $specials['mayDay'] = "$year-05-05";
                    break;
                case 5:
                    $specials['mayDay'] = "$year-05-04";
                    break;
                case 6:
                    $specials['mayDay'] = "$year-05-03";
                    break;
            }
        }


        // Whitsun:
        if ($year == 2002) { // Golden Jubilee exception year
            $specials['whitsun'] = "2002-06-03";
        } elseif ($year == 2012) { // Diamond Jubilee exception year
            $specials['whitsun'] = "2012-06-04";
        } else {
            switch (date("w", strtotime("$year-05-31 00:00:00"))) {
                case 0:
                    $specials['whitsun'] = "$year-05-25";
                    break;
                case 1:
                    $specials['whitsun'] = "$year-05-31";
                    break;
                case 2:
                    $specials['whitsun'] = "$year-05-30";
                    break;
                case 3:
                    $specials['whitsun'] = "$year-05-29";
                    break;
                case 4:
                    $specials['whitsun'] = "$year-05-28";
                    break;
                case 5:
                    $specials['whitsun'] = "$year-05-27";
                    break;
                case 6:
                    $specials['whitsun'] = "$year-05-26";
                    break;
            }
        }

        // non-standard South West singing formula:
        switch (date("w", strtotime("$year-05-31 00:00:00"))) {
            case 0:
                $specials['southWestWhitsun'] = "$year-06-06";
                break;
            case 1:
                $specials['southWestWhitsun'] = "$year-06-12";
                break;
            case 2:
                $specials['southWestWhitsun'] = "$year-06-11";
                break;
            case 3:
                $specials['southWestWhitsun'] = "$year-06-10";
                break;
            case 4:
                $specials['southWestWhitsun'] = "$year-06-09";
                break;
            case 5:
                $specials['southWestWhitsun'] = "$year-06-08";
                break;
            case 6:
                $specials['southWestWhitsun'] = "$year-06-07";
                break;
        }

        // Independence Day
        $specials['independence'] = "$year-07-04";


        // First fifth Sunday after the 4th July: (Young people's convention - SAB)
        switch (date("w", strtotime("$year-07-04"))) {
            case 0:
                $specials['5SU47'] = "$year-08-29";
                break;
            case 1:
                $specials['5SU47'] = "$year-07-31";
                break;
            case 2:
                $specials['5SU47'] = "$year-07-30";
                break;
            case 3:
                $specials['5SU47'] = "$year-07-29";
                break;
            case 4:
                $specials['5SU47'] = "$year-09-29";
                break;
            case 5:
                $specials['5SU47'] = "$year-08-31";
                break;
            case 6:
                $specials['5SU47'] = "$year-08-30";
                break;
        }


        // Summer Bank Holiday: (last Mon in Aug)
        switch (date("w", strtotime("$year-08-31 00:00:00"))) {
            case 0:
                $specials['summer'] = "$year-08-25";
                break;
            case 1:
                $specials['summer'] = "$year-08-31";
                break;
            case 2:
                $specials['summer'] = "$year-08-30";
                break;
            case 3:
                $specials['summer'] = "$year-08-29";
                break;
            case 4:
                $specials['summer'] = "$year-08-28";
                break;
            case 5:
                $specials['summer'] = "$year-08-27";
                break;
            case 6:
                $specials['summer'] = "$year-08-26";
                break;
        }


        // non-standard Scottish Shenandoah formula:
        switch (date("w", strtotime("$year-10-16 00:00:00"))) {
            case 0:
                $specials['scottishShenandoah'] = "$year-10-22";
                break;
            case 1:
                $specials['scottishShenandoah'] = "$year-10-21";
                break;
            case 2:
                $specials['scottishShenandoah'] = "$year-10-20";
                break;
            case 3:
                $specials['scottishShenandoah'] = "$year-10-19";
                break;
            case 4:
                $specials['scottishShenandoah'] = "$year-10-18";
                break;
            case 5:
                $specials['scottishShenandoah'] = "$year-10-17";
                break;
            case 6:
                $specials['scottishShenandoah'] = "$year-10-16";
                break;
        }

        // Thanksgiving: (Fourth Thu in Nov)
        switch (date("w", strtotime("$year-11-24 00:00:00"))) {
            case 0:
                $specials['thanksgiving'] = "$year-11-28";
                break;
            case 1:
                $specials['thanksgiving'] = "$year-11-27";
                break;
            case 2:
                $specials['thanksgiving'] = "$year-11-26";
                break;
            case 3:
                $specials['thanksgiving'] = "$year-11-25";
                break;
            case 4:
                $specials['thanksgiving'] = "$year-11-24";
                break;
            case 5:
                $specials['thanksgiving'] = "$year-11-23";
                break;
            case 6:
                $specials['thanksgiving'] = "$year-11-22";
                break;
        }

        // Christmas:
        $specials['christmas'] = "$year-12-25";
        $specials['boxingDay'] = "$year-12-26";

        return $specials;
    }

    /**
     * Converts array of special days into array of DateTime objects
     *
     * TODO control inputs and test
     *
     * @param array $special
     * @return array
     */
    public function ymdToDatetime(array $special): array
    {
        foreach ($special as $k => $date) {
            // TODO pass timezone to this fn?
            $special[$k] = \DateTime::createFromFormat('!Y-m-d', $date, new \DateTimeZone('UTC'));
        }
        return $special;
    }

    /**
     * Finds closest DateTime object in array to $needle
     *
     * @param \DateTime $needle
     * @param array $haystack
     * @return void
     */
    private function findClosest(\DateTime $needle, array $haystack): array // TODO return type?
    {
        foreach ($haystack as $k => $hay) {
            $interval[$k] = (int)$hay->diff($needle)->format('%R%a');
        }

        uasort($interval, array($this, 'absCompare'));
        $closest = key($interval);

        return ['date' => $closest, 'offset' => $interval[$closest]];
    }

    /**
     * Comparison function for uasort
     * Orders by absolute value
     * e.g 1,2,-3,4,-5...
     *
     * @param integer $a
     * @param integer $b
     * @return integer
     */
    private function absCompare(int $a, int $b): int
    {
        if (abs($a) == abs($b)) {
            return 0;
        }
        return (abs($a) < abs($b)) ? -1 : 1;
    }

    /**
     * Get sign of number.
     *
     * @param integer $n Number. Could be a float too if needed.
     * @return integer
     */
    private function sign(int $n) : int
    {
        return ($n > 0) - ($n < 0);
    }
}
