<?php
/* this ordinal function from https://stackoverflow.com/a/69284 */
function ordinal($num)
{
    $ones = $num % 10;
    $tens = floor($num / 10) % 10;
    if ($tens == 1) {
        $suff = "th";
    } else {
        switch ($ones) {
            case 1: $suff = "st"; break;
            case 2: $suff = "nd"; break;
            case 3: $suff = "rd"; break;
            default: $suff = "th";
        }
    }
    return $num . $suff;
}

/* from http://webdeveloperblog.tiredmachine.com/php-converting-an-integer-123-to-ordinal-word-firstsecondthird/ */
function numToOrdinalWord($num)
{
    $first_word = array('eth','first','second','third','fourth','fifth','sixth','seventh','eighth','ninth','tenth','elevents','twelfth','thirteenth','fourteenth','fifteenth','sixteenth','seventeenth','eighteenth','nineteenth','twentieth');
    $second_word =array('','','twenty','thirty','forty','fifty');

    if ($num <= 20) {
        return $first_word[$num];
    }

    $first_num = substr($num, -1, 1);
    $second_num = substr($num, -2, 1);

    return $string = str_replace('y-eth', 'ieth', $second_word[$second_num].'-'.$first_word[$first_num]);
}

/* from https://stackoverflow.com/a/15017743 */
function find_closest_col1($array, $date)
{
    foreach ($array as $day) {
        $interval[] = abs(strtotime($date) - strtotime($day[0]));
    }

    asort($interval);
    $closest = key($interval);

    //return $array[$closest];
    return $closest;
}
function find_closest($array, $date)
{
    //$count = 0;
    foreach ($array as $day) {
        //$interval[$count] = abs(strtotime($date) - strtotime($day));
        $interval[] = abs(strtotime($date) - strtotime($day));
        //$count++;
    }

    asort($interval);
    $closest = key($interval);

    echo $array[$closest];
}
/* from https://stackoverflow.com/a/834355 */
function startsWith($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}
