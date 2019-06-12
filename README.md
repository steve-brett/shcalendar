# shcalendar
Formula date manager for Sacred Harp singings

http://stevebrett.nfshost.com/shcalendar/

Annual Sacred Harp singings are organised using date formulae, usually relating to the day's position in the month (e.g. second Sunday), or a special date (e.g. Palm Sunday).

This tool allows for human input of what could be a quite complex recurrence rule by finding all likely formulae for a specific date. The user can then choose their singing formula from a list of options, and then generate future dates.

The current formula uses an array format that extends the [RFC 5545](https://icalendar.org/iCalendar-RFC-5545/3-8-5-3-recurrence-rule.html) format used by [rlanvin/php-rrule](https://github.com/rlanvin/php-rrule).

`OFFSET`
: How many days the event is from the reference day. For example, the Saturday before the nth 
Sunday has `OFFSET = -1`.
`STARTOFFSET`
: For multi-day events, how many days the start of the event is before the end.
`SPECIAL`
: The key of an array of special events.

## Examples
The Saturday before the second Sunday in May

```php
['BYMONTH' => 5,
   'BYDAY' => '2SU',
  'OFFSET' => -1]
```

The second Saturday in February

```php
['BYMONTH' => 2,
   'BYDAY' => '2SA']
```

The Saturday after the Whitsun bank holiday

```php
['SPECIAL' => 'whitsun',
  'OFFSET' => +5]
```

The Saturday before the first fifth Sunday after the 4th July (yes, this is a real singing!)

```php
['SPECIAL' => '5SU47',
  'OFFSET' => -1]
```


## Issues
* Comment code properly and review
* Review simplicity of functions in singingFormula and interpretFormula classes
* Extend `singingFormula::specialDays()` to all special days within a week instead of just nearest

## Future plans
* iCalendar output
* multi-day events
* Incorporate fifth Sundays into formulae
