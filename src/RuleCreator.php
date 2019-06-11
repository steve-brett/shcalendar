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
    if ($start->diff($end)->format('%a') > 6)
    {
      throw new \InvalidArgumentException('Dates must not span more than a week. 
      Got [' . $start->format('Y-m-d') . ', ' . $end->format('Y-m-d') .']');
    }
    $output = [
            'DATE' => \DateTime::createFromFormat(\DateTimeInterface::ATOM, 
                      '2019-08-15T15:52:01+00:00'),
    'START_OFFSET' => -1];
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

    $rule['BYDAY'] = $count . $day;
    $rule['BYMONTH'] = $nextRefDay->format('n');
    $rule['OFFSET'] = $nextRefDay->diff($date)->format('%R%a');

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

    $rule['BYDAY'] = '-1'. $day;
    $rule['BYMONTH'] = $nextRefDay->format('n');
    $rule['OFFSET'] = $nextRefDay->diff($date)->format('%R%a');

    return $rule;
  }

}


?>
