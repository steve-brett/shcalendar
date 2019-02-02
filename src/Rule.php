<?php
declare(strict_types=1);

namespace SHCalendar;

interface Rule{
 public function create(\DateTime $date) : array;
}
?>
