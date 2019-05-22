<?php
declare(strict_types=1);

namespace SHCalendar;

$start = $_GET["start"];
$end = $_GET["end"];

try {
    $start_object = \DateTime::createFromFormat(\DateTimeInterface::ATOM, $start);
    echo $start_object->format('D j M Y');
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), PHP_EOL;
}
