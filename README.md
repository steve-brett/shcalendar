# shcalendar
Formula date manager for Sacred Harp singings

http://www.bristolsacredharp.org/stevetest/

Annual Sacred Harp singings are organised using date formulae, usually relating to the day's position in the month (e.g. second Sunday), or a special date (e.g. Palm Sunday).

This tool allows for human input of what could be a quite complex recurrence rule by finding all likely formulae for a specific date. The user can then choose their singing formula from a list of options, and then generate future dates.

The current formula takes advantage of the PHP `DateTime` object's text-based [relative formats](http://php.net/manual/en/datetime.formats.relative.php). However, I may change this to an array that more closely resembles [RFC 5545](https://icalendar.org/iCalendar-RFC-5545/3-8-5-3-recurrence-rule.html) when revising for iCalendar output.

## Examples
The Saturday before the second Sunday in May
`(second Sunday in May,-1)`

The second Saturday in February
`(second Saturday in February)`

The Saturday after the Whitsun bank holiday
`(special4,5)`

The Saturday before the first fifth Sunday after the 4th July (yes, this is a real singing!)
`(special6,-1)`


## Issues
* Comment code properly and review
* Review simplicity of functions in singingFormula and interpretFormula classes
* Extend `singingFormula::specialDays()` to all special days within a week instead of just nearest

## Future plans
* iCalendar output
* multi-day events
* Incorporate fifth Sundays into formulae
