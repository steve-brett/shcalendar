<?php
declare(strict_types=1);

namespace SacredHarpCalendar;

class Helpers
{

    /**
     * Date validator from http://www.php.net/manual/en/function.checkdate.php#113205
     *
     * @since 1.0.0
     * @param  string $date   Input date
     * @param  string $format Format of input date
     * @return boolean        True if valid
     */
    public static function validateDate(string $date, string $format = 'Y-m-d H:i:s') : bool
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    /**
     * Format minimal date ranges.
     *
     * @since 1.0.0
     * @param \DateTime $start
     * @param \DateTime $end
     * @return string
     */
    public static function formatDateRange(\DateTime $start, \DateTime $end) : string
    {
        // If years different, output full range
        if ($start->format('Y') !== $end->format('Y')) {
            return $start->format('D j F Y') . ' – '. $end->format('D j F Y');
        }

        // If months different, include month
        if ($start->format('m') !== $end->format('m')) {
            return $start->format('D j F') . ' – '. $end->format('D j F');
        }

        // If days different, include day
        if ($start != $end) {
            return $start->format('D j') . ' – '. $end->format('D j F');
        }

        // Otherwise don't return a range; start and end are same day
        return $end->format('D j F');
    }

    /**
     * Format year range
     *
     * @since 1.0.0
     * @param \DateTime $start
     * @param \DateTime $end
     * @return string
     */
    public static function formatYearRange(\DateTime $start, \DateTime $end) : string
    {
        // If years different, output full range
        if ($start->format('Y') !== $end->format('Y')) {
            return $start->format('Y') . '/'. $end->format('y');
        }

        // Otherwise don't return a range; start and end are same day
        return $end->format('Y');
    }
}
