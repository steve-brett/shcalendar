<?php
declare(strict_types=1);

namespace SHCalendar;

class RuleCreator
{
  private static $dayFormats = [
    'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun',
    'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'
  ];

  public function create(\DateTime $start, \DateTime $end = null): array
  {
    $input = $this->span($start, $end);
    $date = $input['DATE'];

    $day = $date->format('N');

    try {
      $output['NTHSUN'] = $this->nthDay($date, 'Sun');
    } catch (\Exception $e) { }
    
    try {
      $output['LASTSUN'] = $this->lastDay($date, 'Sun');
    } catch (\Exception $e) { }

    if ($day != 7)
    {
      try {
        $output['NTHDAY'] = $this->nthDay($date);
      } catch (\Exception $e) { }

      try {
        $output['LASTDAY'] = $this->lastDay($date);
      } catch (\Exception $e) { }
    }

    try {
      $output['SPECIAL'] = $this->special($date);
    } catch (\Exception $e) { }


    // Add STARTOFFSET to each array
    if (isset($input['STARTOFFSET']))
    {
      foreach ($output as &$rule)
      {
        $rule['STARTOFFSET'] = $input['STARTOFFSET'];
      }
      unset($rule);
    }

    return $output;
  }

  public function span(\DateTime $start, \DateTime $end = null): array
  {
    // Time will mess with our calculations - set to midnight
    $start->setTime(0,0,0);
    if ($end == null)
    {
      $output['DATE'] = $start;
      return $output;
    }
    $end->setTime(0,0,0);

    $diff = $end->diff($start)->format('%r%a');

    if (abs($diff) > 6)
    {
      throw new \InvalidArgumentException('Dates must not span more than a week. 
      Got [' . $start->format('Y-m-d') . ', ' . $end->format('Y-m-d') .']');
    }
    // Swap if end is before start
    if ($diff > 0)
    {
        $tmp = $start;
      $start = $end;
        $end = $tmp;

      $diff = -$diff;
    }

    if (abs($diff) > 0)
    {
      $output['STARTOFFSET'] = (int)$diff;
    }
     
    $output['DATE'] = $end;
    return $output;
  }

  // TODO change $refday to format 'N'
  public function nthDay(\DateTime $date, string $refDay = null): array
  {
    if ($date < \DateTime::createFromFormat('Y-m-d', '1800-01-01')) {
      throw new \InvalidArgumentException('Date must be 1800-01-01 or after. 
      Got [' . $date->format('Y-m-d') .']');
    }

    if (isset($refDay) && !in_array(strtolower($refDay), $this::$dayFormats) )
    {
      throw new \InvalidArgumentException('Reference day must be valid. 
      Got [' . $refDay .']');
    }

    // Find next instance of $refDay
    $nextRefDay = clone $date;
    if ( isset($refDay) )
    {
      $nextRefDay->modify('this ' . $refDay);
    }

    // Find position of refDay in month
    $count = floor(($nextRefDay->format('d') - 1) / 7) + 1;
    if ($count == 5) {
      throw new \InvalidArgumentException('Date is 5th of type in month - not annual. 
      Got [' . $date->format('Y-m-d') .']');
    }

    $day = strtoupper(substr($nextRefDay->format('D'), 0, -1));
    $offset = $nextRefDay->diff($date)->format('%R%a');

    $rule['BYDAY'] = $count . $day;
    $rule['BYMONTH'] = (int)$nextRefDay->format('n');
    if (abs($offset) > 0)
    {
      $rule['OFFSET'] = (int)$offset;
    }

    return $rule;
  }

  // TODO change $refday to format 'N'
  public function lastDay(\DateTime $date, string $refDay = null): array
  {
    if ($date < \DateTime::createFromFormat('Y-m-d', '1800-01-01')) {
      throw new \InvalidArgumentException('Date must be 1800-01-01 or after. 
      Got [' . $date->format('Y-m-d') .']');
    }

    if (isset($refDay) && !in_array(strtolower($refDay), $this::$dayFormats) )
    {
      throw new \InvalidArgumentException('Reference day must be valid. Got [' . $refDay .']');
    }

    // Find next instance of $refDay
    $nextRefDay = clone $date;
    if ( isset($refDay) )
    {
      $nextRefDay->modify('this ' . $refDay);
    }

    $monthCheck = clone $nextRefDay;
    $monthCheck = $monthCheck->modify('+1 week');
    if ($nextRefDay->format('m') == $monthCheck->format('m')) {
      throw new \InvalidArgumentException('Date is not last of its type in month. 
      Got [' . $date->format('Y-m-d') .']');
    }

    $day = strtoupper(substr($nextRefDay->format('D'), 0, -1));
    $offset = $nextRefDay->diff($date)->format('%R%a');

    $rule['BYDAY'] = '-1'. $day;
    $rule['BYMONTH'] = (int)$nextRefDay->format('n');
    if (abs($offset) > 0)
    {
      $rule['OFFSET'] = (int)$offset;
    }

    return $rule;
  }

  public function special(\DateTime $date): array
  {
    if ($date < \DateTime::createFromFormat('Y-m-d', '1800-01-01')) {
      throw new \InvalidArgumentException('Date must be 1800-01-01 or after. 
      Got [' . $date->format('Y-m-d') .']');
    }

    $year = (int)$date->format('Y');
    $special = $this->calculateSpecial($year);
    $special = $this->ymd_to_datetime($special);
    $closest = $this->find_closest($date, $special);

    $rule['SPECIAL'] = $closest['date'];
    if ( abs($closest['offset']) > 7 ) {
      // TODO Is this the right thing to do, or return false?
      throw new \InvalidArgumentException('Not within a week of a special day. 
      Got [' . $date->format('Y-m-d') .']');
    }
    if ( abs($closest['offset']) > 0 ) {
      $rule['OFFSET'] = $closest['offset'];
    }
    return $rule;
  }

  public function calculateSpecial(int $year = null): array 
  {
      // default to current year if not set
      $year = $year ?: date('Y');

      $bankHols = array(); 

      // New year's day:
      $bankHols['newYear'] = "$year-01-01";
      
      // Palm Sunday:
      $bankHols['palmSunday'] = date("Y-m-d", strtotime("+".(easter_days($year) - 7)." days", strtotime("$year-03-21 00:00:00")));
   
      /*
      // Good friday:
      $bankHols['goodFriday'] = date("Y-m-d", strtotime("+".(easter_days($year) - 2)." days", strtotime("$year-03-21 00:00:00")));
      */

      // Easter:
      $bankHols['easter'] = date("Y-m-d", strtotime("+".easter_days($year)." days", strtotime("$year-03-21 00:00:00")));

      /*
      // Easter Monday:
      $bankHols['easterMonday'] = date("Y-m-d", strtotime("+".(easter_days($year) + 1)." days", strtotime("$year-03-21 00:00:00")));
      */

      // May Day:
      if ($year == 1995) {
          $bankHols['mayDay'] = "1995-05-08"; // VE day 50th anniversary year exception
      } else {
          switch (date("w", strtotime("$year-05-01 00:00:00"))) {
              case 0:
                  $bankHols['mayDay'] = "$year-05-02";
                  break;
              case 1:
                  $bankHols['mayDay'] = "$year-05-01";
                  break;
              case 2:
                  $bankHols['mayDay'] = "$year-05-07";
                  break;
              case 3:
                  $bankHols['mayDay'] = "$year-05-06";
                  break;
              case 4:
                  $bankHols['mayDay'] = "$year-05-05";
                  break;
              case 5:
                  $bankHols['mayDay'] = "$year-05-04";
                  break;
              case 6:
                  $bankHols['mayDay'] = "$year-05-03";
                  break;
          }
      }
     

      // Whitsun:
      if ($year == 2002) { // Golden Jubilee exception year
          $bankHols['whitsun'] = "2002-06-03";
      } elseif ($year == 2012) { // Diamond Jubilee exception year
          $bankHols['whitsun'] = "2012-06-04";
      } else {
          switch (date("w", strtotime("$year-05-31 00:00:00"))) {
              case 0:
                  $bankHols['whitsun'] = "$year-05-25";
                  break;
              case 1:
                  $bankHols['whitsun'] = "$year-05-31";
                  break;
              case 2:
                  $bankHols['whitsun'] = "$year-05-30";
                  break;
              case 3:
                  $bankHols['whitsun'] = "$year-05-29";
                  break;
              case 4:
                  $bankHols['whitsun'] = "$year-05-28";
                  break;
              case 5:
                  $bankHols['whitsun'] = "$year-05-27";
                  break;
              case 6:
                  $bankHols['whitsun'] = "$year-05-26";
                  break;
          }
      }


      // Independence Day
      $bankHols['independence'] = "$year-07-04";
    

      // First fifth Sunday after the 4th July: (Young people's convention - SAB)
      switch (date("w", strtotime("$year-07-04"))) {
          case 0:
              $bankHols['5SU47'] = "$year-08-29";
              break;
          case 1:
              $bankHols['5SU47'] = "$year-07-31";
              break;
          case 2:
              $bankHols['5SU47'] = "$year-07-30";
              break;
          case 3:
              $bankHols['5SU47'] = "$year-07-29";
              break;
          case 4:
              $bankHols['5SU47'] = "$year-09-29";
              break;
          case 5:
              $bankHols['5SU47'] = "$year-08-31";
              break;
          case 6:
              $bankHols['5SU47'] = "$year-08-30";
              break;
      }


      // Summer Bank Holiday: (last Mon in Aug)
      switch (date("w", strtotime("$year-08-31 00:00:00"))) {
          case 0:
              $bankHols['summer'] = "$year-08-25";
              break;
          case 1:
              $bankHols['summer'] = "$year-08-31";
              break;
          case 2:
              $bankHols['summer'] = "$year-08-30";
              break;
          case 3:
              $bankHols['summer'] = "$year-08-29";
              break;
          case 4:
              $bankHols['summer'] = "$year-08-28";
              break;
          case 5:
              $bankHols['summer'] = "$year-08-27";
              break;
          case 6:
              $bankHols['summer'] = "$year-08-26";
              break;
      }
      

      // Thanksgiving: (Fourth Thu in Nov)
      switch (date("w", strtotime("$year-11-24 00:00:00"))) {
          case 0:
              $bankHols['thanksgiving'] = "$year-11-28";
              break;
          case 1:
              $bankHols['thanksgiving'] = "$year-11-27";
              break;
          case 2:
              $bankHols['thanksgiving'] = "$year-11-26";
              break;
          case 3:
              $bankHols['thanksgiving'] = "$year-11-25";
              break;
          case 4:
              $bankHols['thanksgiving'] = "$year-11-24";
              break;
          case 5:
              $bankHols['thanksgiving'] = "$year-11-23";
              break;
          case 6:
              $bankHols['thanksgiving'] = "$year-11-22";
              break;
      }
      
      // Christmas:
      $bankHols['christmas'] = "$year-12-25";
      $bankHols['boxingDay'] = "$year-12-26";

      return $bankHols;
  }

  public function ymd_to_datetime(array $special) : array
  {
    foreach ($special as $k => $date )
    {
      // TODO pass timezone to this fn?
      $special[$k] = \DateTime::createFromFormat('!Y-m-d', $date, new \DateTimeZone('UTC'));
    }
    return $special;
  }

  protected function find_closest(\DateTime $needle, array $haystack): array // TODO return type?
  {
      foreach ($haystack as $k => $hay) {
          $interval[$k] = (int)$hay->diff($needle)->format('%R%a');
      }

      uasort($interval, array($this, 'abs_compare')); 
      $closest = key($interval);

      return ['date' => $closest, 'offset' => $interval[$closest]];
  }

  protected function abs_compare(int $a, int $b): int 
  {
    if (abs($a) == abs($b)) {
        return 0;
    }
    return (abs($a) < abs($b)) ? -1 : 1;
  }

}
