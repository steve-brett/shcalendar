<?php

/*
*    Function to calculate which days are British bank holidays (England & Wales) for a given year.
*
*    Created by David Scourfield, 07 August 2006, and released into the public domain.
*    Anybody may use and/or modify this code.
*
*    Modified by Steve Brett 2018 for Sacred Harp calendar use.
*
*    USAGE:
*
*    array calculateBankHolidays(int $yr)
*
*    ARGUMENTS
*
*    $yr = 4 digit numeric representation of the year (eg 1997).
*
*    RETURN VALUE
*
*    Returns an array of strings where each string is a date of a bank holiday in the format "yyyy-mm-dd".
*
*    See example below
*
*/

function calculateBankHolidays($yr)
{
    //default to current year if not set - SAB
    $yr = $yr ?: date('Y');

    $bankHols = array();
    $key = 0;

    // New year's day:
    $bankHols[] = "$yr-01-01";
    $bankHols[$key] = array($bankHols[$key], "New Year's Day"); //SAB
    $key++;

    // Palm Sunday:
    $bankHols[] = date("Y-m-d", strtotime("+".(easter_days($yr) - 7)." days", strtotime("$yr-03-21 12:00:00")));
    $bankHols[$key] = array($bankHols[$key], "Palm Sunday"); //SAB
    $key++;

    /*
    // Good friday:
    $bankHols[] = date("Y-m-d", strtotime("+".(easter_days($yr) - 2)." days", strtotime("$yr-03-21 12:00:00")));
    $bankHols[$key] = array($bankHols[$key], "Good Friday"); //SAB
    $key++;
    */

    // Easter:
    $bankHols[] = date("Y-m-d", strtotime("+".easter_days($yr)." days", strtotime("$yr-03-21 12:00:00")));
    $bankHols[$key] = array($bankHols[$key], "Easter"); //SAB
    $key++;

    /*
    // Easter Monday:
    $bankHols[] = date("Y-m-d", strtotime("+".(easter_days($yr) + 1)." days", strtotime("$yr-03-21 12:00:00")));
    $bankHols[$key] = array($bankHols[$key], "Easter Monday"); //SAB
    $key++;
    */

    // May Day:
    if ($yr == 1995) {
        $bankHols[] = "1995-05-08"; // VE day 50th anniversary year exception
    } else {
        switch (date("w", strtotime("$yr-05-01 12:00:00"))) {
            case 0:
                $bankHols[] = "$yr-05-02";
                break;
            case 1:
                $bankHols[] = "$yr-05-01";
                break;
            case 2:
                $bankHols[] = "$yr-05-07";
                break;
            case 3:
                $bankHols[] = "$yr-05-06";
                break;
            case 4:
                $bankHols[] = "$yr-05-05";
                break;
            case 5:
                $bankHols[] = "$yr-05-04";
                break;
            case 6:
                $bankHols[] = "$yr-05-03";
                break;
        }
    }
    $bankHols[$key] = array($bankHols[$key], "May Day bank holiday"); //SAB
    $key++;

    // Whitsun:
    if ($yr == 2002) { // exception year
        $bankHols[] = "2002-06-03";
        $bankHols[] = "2002-06-04";
    } else {
        switch (date("w", strtotime("$yr-05-31 12:00:00"))) {
            case 0:
                $bankHols[] = "$yr-05-25";
                break;
            case 1:
                $bankHols[] = "$yr-05-31";
                break;
            case 2:
                $bankHols[] = "$yr-05-30";
                break;
            case 3:
                $bankHols[] = "$yr-05-29";
                break;
            case 4:
                $bankHols[] = "$yr-05-28";
                break;
            case 5:
                $bankHols[] = "$yr-05-27";
                break;
            case 6:
                $bankHols[] = "$yr-05-26";
                break;
        }
    }
    $bankHols[$key] = array($bankHols[$key], "Whitsun bank holiday"); //SAB
    $key++;

    // Independence Day
    $bankHols[] = "$yr-07-04";
    $bankHols[$key] = array($bankHols[$key], "Independence Day"); //SAB
    $key++;

    // First fifth Sunday after the 4th July: (Young people's convention - SAB)
    switch (date("w", strtotime("$yr-07-04"))) {
        case 0:
            $bankHols[] = "$yr-08-29";
            break;
        case 1:
            $bankHols[] = "$yr-07-31";
            break;
        case 2:
            $bankHols[] = "$yr-07-30";
            break;
        case 3:
            $bankHols[] = "$yr-07-29";
            break;
        case 4:
            $bankHols[] = "$yr-09-29";
            break;
        case 5:
            $bankHols[] = "$yr-08-31";
            break;
        case 6:
            $bankHols[] = "$yr-08-30";
            break;
    }
    $bankHols[$key] = array($bankHols[$key], "the first fifth Sunday after the 4th July"); //SAB
    $key++;

    // Summer Bank Holiday: (last Mon in Aug)
    switch (date("w", strtotime("$yr-08-31 12:00:00"))) {
        case 0:
            $bankHols[] = "$yr-08-25";
            break;
        case 1:
            $bankHols[] = "$yr-08-31";
            break;
        case 2:
            $bankHols[] = "$yr-08-30";
            break;
        case 3:
            $bankHols[] = "$yr-08-29";
            break;
        case 4:
            $bankHols[] = "$yr-08-28";
            break;
        case 5:
            $bankHols[] = "$yr-08-27";
            break;
        case 6:
            $bankHols[] = "$yr-08-26";
            break;
    }
    $bankHols[$key] = array($bankHols[$key], "Summer Bank Holiday"); //SAB
    $key++;

    // Thanksgiving: (Fourth Thu in Nov)
    switch (date("w", strtotime("$yr-11-24 12:00:00"))) {
        case 0:
            $bankHols[] = "$yr-11-28";
            break;
        case 1:
            $bankHols[] = "$yr-11-27";
            break;
        case 2:
            $bankHols[] = "$yr-11-26";
            break;
        case 3:
            $bankHols[] = "$yr-11-25";
            break;
        case 4:
            $bankHols[] = "$yr-11-24";
            break;
        case 5:
            $bankHols[] = "$yr-11-23";
            break;
        case 6:
            $bankHols[] = "$yr-11-22";
            break;
    }
    $bankHols[$key] = array($bankHols[$key], "Thanksgiving"); //SAB
    $key++;


    // Christmas:
    /*
    switch (date("w", strtotime("$yr-12-25 12:00:00"))) {
        case 5:
            $bankHols[] = "$yr-12-25";
            $bankHols[] = "$yr-12-28";
            break;
        case 6:
            $bankHols[] = "$yr-12-27";
            $bankHols[] = "$yr-12-28";
            break;
        case 0:
            $bankHols[] = "$yr-12-26";
            $bankHols[] = "$yr-12-27";
            break;
        default:
            $bankHols[] = "$yr-12-25";
            $bankHols[] = "$yr-12-26";
    }
    */
    $bankHols[] = "$yr-12-25";
    $bankHols[] = "$yr-12-26";
    $bankHols[$key] = array($bankHols[$key], "Christmas Day"); //SAB
    $key++;
    $bankHols[$key] = array($bankHols[$key], "Boxing Day"); //SAB
    $key++;

    // Millenium eve
    if ($yr == 1999) {
        $bankHols[] = "1999-12-31";
        $bankHols[$key] = array($bankHols[$key], "Millenium Eve"); //SAB
        $key++;
    }


    return $bankHols;
}

function calculateFifthSundays($yr)
{
    //default to current year if not set - SAB
    $yr = $yr ?: date('Y');

    $fifthSundays = array();

    // Fifth Sundays before February (unaffected by leap year)
    switch (date("N", strtotime("$yr-01-01 12:00:00"))) {
        case 5: // if 1 Jan Friday
            $fifthSundays[] = "$yr-01-31";
            break;
        case 6: // if Saturday
            $fifthSundays[] = "$yr-01-30";
            break;
        case 7: // if Sunday
            $fifthSundays[] = "$yr-01-29";
            break;

    }

    // If a leap year with five Sundays in Feb
    if (date("L", strtotime("$yr-01-01 12:00:00")) == 1) {
        if (date("N", strtotime("$yr-02-29 12:00:00")) == 7) {
            $fifthSundays[] = "$yr-02-29";
        }
    }
    // Anything after 1 March is unaffected by leap year if calculated from then:
    switch (date("N", strtotime("$yr-03-01 12:00:00"))) {
        case 1: // if 1 March Monday
            $fifthSundays[] = "$yr-05-30";
            $fifthSundays[] = "$yr-08-29";
            $fifthSundays[] = "$yr-10-31";
            break;
        case 2: // if Tuesday
            $fifthSundays[] = "$yr-05-29";
            $fifthSundays[] = "$yr-07-31";
            $fifthSundays[] = "$yr-10-30";
            break;
        case 3: // if Wednesday
            $fifthSundays[] = "$yr-04-30";
            $fifthSundays[] = "$yr-07-30";
            $fifthSundays[] = "$yr-10-29";
            $fifthSundays[] = "$yr-12-31";
            break;
        case 4: // if Thursday
            $fifthSundays[] = "$yr-04-29";
            $fifthSundays[] = "$yr-07-29";
            $fifthSundays[] = "$yr-09-30";
            $fifthSundays[] = "$yr-12-30";
            break;
        case 5: // if Friday
            $fifthSundays[] = "$yr-03-31";
            $fifthSundays[] = "$yr-06-30";
            $fifthSundays[] = "$yr-09-29";
            $fifthSundays[] = "$yr-12-29";
            break;
        case 6: // if Saturday
            $fifthSundays[] = "$yr-03-30";
            $fifthSundays[] = "$yr-06-29";
            $fifthSundays[] = "$yr-08-31";
            $fifthSundays[] = "$yr-11-30";
            break;
        case 7: // if Sunday
            $fifthSundays[] = "$yr-03-29";
            $fifthSundays[] = "$yr-05-31";
            $fifthSundays[] = "$yr-08-30";
            $fifthSundays[] = "$yr-11-29";
            break;

    }

    return $fifthSundays;
}

/*
*    EXAMPLE:
*
*

header("Content-type: text/plain");

$bankHolsThisYear = calculateBankHolidays('2007');

print_r($bankHolsThisYear);
*/
