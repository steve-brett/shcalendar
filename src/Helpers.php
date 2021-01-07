<?php
declare(strict_types=1);

namespace SHCalendar;

class Helpers
{

    /**
     * Date validator from http://www.php.net/manual/en/function.checkdate.php#113205
     * @param  string $date   Input date
     * @param  string $format Format of input date
     * @return boolean        True if valid
     */
    public static function validateDate(string $date, string $format = 'Y-m-d H:i:s') : bool
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}
