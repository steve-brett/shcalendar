<?php
/* this ordinal function from https://stackoverflow.com/a/69284 */
function ordinal($num) {
    $ones = $num % 10;
    $tens = floor($num / 10) % 10;
    if ($tens == 1) {
        $suff = "th";
    } else {
        switch ($ones) {
            case 1 : $suff = "st"; break;
            case 2 : $suff = "nd"; break;
            case 3 : $suff = "rd"; break;
            default : $suff = "th";
        }
    }
    return $num . $suff;
}

/* from https://stackoverflow.com/a/15017743 */
function find_closest($array, $date)
{
    foreach($array as $day)
    {
        $interval[] = abs(strtotime($date) - strtotime($day));
    }

    asort($interval);
    $closest = key($interval);

    return $array[$closest];
}
?>
