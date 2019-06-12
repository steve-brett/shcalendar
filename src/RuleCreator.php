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
    $terms = $this->span($start, $end);
    $date = $terms['DATE'];

    $day = $date->format('N');

    try {
      $output['NTHSUN'] = $this->nthDay($date, 'Sun');
      if (isset($terms['STARTOFFSET']))
      {
        $output['NTHSUN']['STARTOFFSET'] =  $terms['STARTOFFSET'];
      }
    } catch (\Exception $e) { }
    
    if ($day != 7)
    {
      try {
        $output['NTHDAY'] = $this->nthDay($date);
      } catch (\Exception $e) { }
    }

    try {
      $output['LASTSUN'] = $this->lastDay($date, 'Sun');
    } catch (\Exception $e) { }

    try {
      $output['LASTDAY'] = $this->lastDay($date);
    } catch (\Exception $e) { }

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
      $output['START_OFFSET'] = (int)$diff;
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

}
