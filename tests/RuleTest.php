<?php

declare(strict_types=1);

namespace SHCalendar\Test;

use SHCalendar\Rule;
use PHPUnit\Framework\TestCase;

class RuleTest extends TestCase # Has to be [ClassName]Test
{
    /**
     * @var Rule
     */
    private $rule;

    protected function setUp()
    {
        $this->rule = new Rule();
    }

    public function happyPathReadableDataProvider(): array
    {
        return [
            [
                'The first Sunday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                ]
            ],

            [
                'The second Sunday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '2SU',
                ]
            ],

            [
                'The third Sunday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '3SU',
                ]
            ],

            [
                'The fourth Sunday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '4SU',
                ]
            ],

            [
                'The last Sunday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '-1SU',
                ]
            ],

            [
                'The first Sunday in June',
                [
                    'BYMONTH' => 6,
                    'BYDAY' => '1SU',
                ]
            ],

            [
                'The first Sunday in July',
                [
                    'BYMONTH' => 7,
                    'BYDAY' => '1SU',
                ]
            ],

            [
                'The first Sunday in August',
                [
                    'BYMONTH' => 8,
                    'BYDAY' => '1SU',
                ]
            ],

            [
                'The last Sunday in August',
                [
                    'BYMONTH' => 8,
                    'BYDAY' => '-1SU',
                ]
            ],

            [
                'The first Saturday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SA',
                ]
            ],

            [
                'The first Monday in January',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '1MO',
                ]
            ],

            [
                'The last Tuesday in January',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '-1TU',
                ]
            ],

            [
                'The Saturday before the first Sunday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA'
                ]
            ],

            [
                'The Sunday before the first Monday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1MO',
                    'OFFSET' => '-1SU'
                ]
            ],

            [
                'The Monday before the first Tuesday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1TU',
                    'OFFSET' => '-1MO'
                ]
            ],

            [
                'The Friday before the first Sunday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1FR'
                ]
            ],

            [
                'The Thursday before the first Sunday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1TH'
                ]
            ],
            // Positive offset
            [
                'The Monday after the first Sunday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                    'OFFSET' => '1MO',
                ]
            ],

        ];
    }

    /**
     * @dataProvider happyPathReadableDataProvider
     */
    public function testHappyPathReadable(string $expectedValue, array $inputValue): void
    {
        $this->assertEquals($expectedValue, $this->rule->readable($inputValue));
    }

    public function happyPathReadableSpecialDataProvider(): array
    {
        return [
            [
                'New Year\'s Day',
                [
                    'SPECIAL' => 'newYear',
                ]
            ],

            [
                'Palm Sunday',
                [
                    'SPECIAL' => 'palmSunday',
                ]
            ],

            [
                'Easter',
                [
                    'SPECIAL' => 'easter',
                ]
            ],

            [
                'May Day bank holiday',
                [
                    'SPECIAL' => 'mayDay',
                ]
            ],

            [
                'the Whitsun bank holiday',
                [
                    'SPECIAL' => 'whitsun',
                ]
            ],

            [
                'Independence Day',
                [
                    'SPECIAL' => 'independence',
                ]
            ],

            [
                'the first fifth Sunday after the 4th July',
                [
                    'SPECIAL' => '5SU47',
                ]
            ],

            [
                'the summer bank holiday',
                [
                    'SPECIAL' => 'summer',
                ]
            ],

            [
                'Thanksgiving',
                [
                    'SPECIAL' => 'thanksgiving',
                ]
            ],

            [
                'Christmas Day',
                [
                    'SPECIAL' => 'christmas',
                ]
            ],

            [
                'Boxing Day',
                [
                    'SPECIAL' => 'boxingDay',
                ]
            ],

            [
                'The Saturday before New Year\'s Day',
                [
                    'SPECIAL' => 'newYear',
                    'OFFSET' => '-1SA',
                ]
            ],




        ];
    }

    /**
     * @dataProvider happyPathReadableSpecialDataProvider
     */
    public function testHappyPathReadableSpecial(string $expectedValue, array $inputValue): void
    {
        $this->assertEquals($expectedValue, $this->rule->readable($inputValue));
    }

    public function startOffsetOfSingleDayReturnsCorrectDayInReadableOutputDataProvider(): array
    {
        return [
            [
                'The first Sunday in May and the Saturday before',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                    'STARTOFFSET' => -1,
                ]
            ],

            [
                'The first Saturday in May and the Friday before',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SA',
                    'STARTOFFSET' => -1,
                ]
            ],
        ];
    }
    /**
     * @dataProvider startOffsetOfSingleDayReturnsCorrectDayInReadableOutputDataProvider
     */
    public function testStartOffsetOfSingleDayReturnsCorrectDayInReadableOutput(string $expectedValue, array $inputValue): void
    {
        $this->assertEquals($expectedValue, $this->rule->readable($inputValue));
    }

    public function startOffsetOfMultipleDaysReturnsCorrectDayInReadableOutputDataProvider(): array
    {
        return [
            [
                'The first Sunday in May and the Friday and Saturday before',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                    'STARTOFFSET' => -2,
                ]
            ],
            [
                'The first Monday in May and the Saturday and Sunday before',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1MO',
                    'STARTOFFSET' => -2,
                ]
            ],
            [
                'The first Sunday in May and the Thursday to Saturday before',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                    'STARTOFFSET' => -3,
                ]
            ],
            [
                'The first Wednesday in May and the Thursday to Tuesday before',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1WE',
                    'STARTOFFSET' => -6,
                ]
            ],

        ];
    }
    /**
     * @dataProvider startOffsetOfMultipleDaysReturnsCorrectDayInReadableOutputDataProvider
     */
    public function testStartOffsetOfMultipleDaysReturnsCorrectDayInReadableOutput(string $expectedValue, array $inputValue): void
    {
        $this->assertEquals($expectedValue, $this->rule->readable($inputValue));
    }

    public function startOffsetWithOffsetReturnsCorrectDayInReadableOutputDataProvider(): array
    {
        return [
            [
                'The Friday and Saturday before the first Sunday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA',
                    'STARTOFFSET' => -1,
                ]
            ],
            [
                'The Thursday and Friday before the first Sunday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1FR',
                    'STARTOFFSET' => -1,
                ]
            ],
            [
                'The Thursday to Saturday before the first Sunday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA',
                    'STARTOFFSET' => -2,
                ]
            ],
            [
                'The Monday after the first Sunday in May and the Sunday before',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                    'OFFSET' => '1MO',
                    'STARTOFFSET' => -1,
                ]
            ],
            [
                'The Monday after the first Sunday in May and the Saturday and Sunday before',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                    'OFFSET' => '1MO',
                    'STARTOFFSET' => -2,
                ]
            ],

        ];
    }
    /**
     * @dataProvider startOffsetWithOffsetReturnsCorrectDayInReadableOutputDataProvider
     */
    public function testStartOffsetWithOffsetReturnsCorrectDayInReadableOutput(string $expectedValue, array $inputValue): void
    {
        $this->assertEquals($expectedValue, $this->rule->readable($inputValue));
    }

    public function startOffsetWithSpecialReturnsCorrectReadableOutputDataProvider(): array
    {
        return [
            [
                'New Year\'s Day and the day before',
                [
                    'SPECIAL' => 'newYear',
                    'STARTOFFSET' => -1,
                ]
            ],
            [
                'New Year\'s Day and the two preceding days',
                [
                    'SPECIAL' => 'newYear',
                    'STARTOFFSET' => -2,
                ]
            ],
            [
                'New Year\'s Day and the six preceding days',
                [
                    'SPECIAL' => 'newYear',
                    'STARTOFFSET' => -6,
                ]
            ],

        ];
    }
    /**
     * @dataProvider startOffsetWithSpecialReturnsCorrectReadableOutputDataProvider
     */
    public function testStartOffsetWithSpecialReturnsCorrectReadableOutput(string $expectedValue, array $inputValue): void
    {
        $this->assertEquals($expectedValue, $this->rule->readable($inputValue));
    }

    public function invalidData(): array
    {
        return [
            [['BYDAY' => '1SU',]],
            [['BYMONTH' => 5,]],
            [[
                'BYMONTH' => 5,
                'BYDAY' => '1SU',
                'OFFSET' => 7
            ]],
            [[
                'BYMONTH' => 13,
                'BYDAY' => '1SU',
            ]],
            [[
                'BYMONTH' => 'xyz',
                'BYDAY' => '1SU',
            ]],
            [[
                'BYMONTH' => -5,
                'BYDAY' => '1SU',
            ]],
            [[
                'BYMONTH' => 5,
                'BYDAY' => 'SU',
            ]],
            [[
                'BYMONTH' => 5,
                'BYDAY' => '1SU',
                'OFFSET' => 'garbage'
            ]],
            // Fine in year but not in month
            // [['BYMONTH' => 5,
            // 'BYDAY' => '6SU',
            // ]],
            // Not valid for Sacred Harp
            // [['BYMONTH' => 5,
            // 'BYDAY' => '-2SU',
            // ]],
            [[
                'SPECIAL' => 'garbage',
            ]],
            // Offset more than a week
            [[
                'SPECIAL' => 'mayDay',
                'OFFSET' => '2TU',
            ]],
            // Positive STARTOFFSET
            [[
                'BYMONTH' => 5,
                'BYDAY' => '1SU',
                'STARTOFFSET' => 1,
            ]],
            // STARTOFFSET too big
            [[
                'BYMONTH' => 5,
                'BYDAY' => '1SU',
                'STARTOFFSET' => -7,
            ]],
            // STARTOFFSET not an integer
            [[
                'BYMONTH' => 5,
                'BYDAY' => '1SU',
                'STARTOFFSET' => 'garbage',
            ]],
        ];
    }

    /**
     * @dataProvider invalidData
     */
    public function testThrowsExceptionReadable(array $inputValue): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->rule->readable($inputValue);
    }


    public function happyPath5545DataProvider(): array
    {
        return [
            // Nth Sunday rules
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=5;BYDAY=1SU',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=5;BYDAY=2SU',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '2SU',
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=5;BYDAY=3SU',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '3SU',
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=5;BYDAY=4SU',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '4SU',
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=5;BYDAY=-1SU',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '-1SU',
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=6;BYDAY=1SU',
                [
                    'BYMONTH' => 6,
                    'BYDAY' => '1SU',
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=7;BYDAY=1SU',
                [
                    'BYMONTH' => 7,
                    'BYDAY' => '1SU',
                ]
            ],
            // Nth day rules
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=1;BYDAY=1SA',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '1SA',
                ]
            ],
            // Sat before first Sunday
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYYEARDAY=-1,1,2,3,4,5,6',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYYEARDAY=31,32,33,34,35,36,37',
                [
                    'BYMONTH' => 2,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            // First week of March:
            // -306,-305,-304,-303,-302,-301,-300
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYYEARDAY=-307,-306,-305,-304,-303,-302,-301',
                [
                    'BYMONTH' => 3,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            // First week of April:
            // -275,-274,-273,-272,-271,-270,-269
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYYEARDAY=-276,-275,-274,-273,-272,-271,-270',
                [
                    'BYMONTH' => 4,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            // First week of May:
            // -245,-244,-243,-242,-241,-240,-239
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYYEARDAY=-246,-245,-244,-243,-242,-241,-240',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            // First week of June:
            // -214,-213,-212,-211,-210,-209,-208
            [
            'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYYEARDAY=-215,-214,-213,-212,-211,-210,-209',
                [
                    'BYMONTH' => 6,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            // Friday before first Sun
            // BYYEARDAY span
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=FR;BYYEARDAY=-2,-1,1,2,3,4,5',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1FR'
                ]
            ],
            // Positive BYYEARDAY
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=FR;BYYEARDAY=30,31,32,33,34,35,36',
                [
                    'BYMONTH' => 2,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1FR'
                ]
            ],
            // Negative BYYEARDAY
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=FR;BYYEARDAY=-308,-307,-306,-305,-304,-303,-302',
                [
                    'BYMONTH' => 3,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1FR'
                ]
            ],
            // Monday before first Sun
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYYEARDAY=-6,-5,-4,-3,-2,-1,1',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1MO'
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYYEARDAY=26,27,28,29,30,31,32',
                [
                    'BYMONTH' => 2,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1MO'
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYYEARDAY=-312,-311,-310,-309,-308,-307,-306',
                [
                    'BYMONTH' => 3,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1MO'
                ]
            ],
            // Positive offsets
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYYEARDAY=2,3,4,5,6,7,8',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '1SU',
                    'OFFSET' => '1MO'
                ]
            ],

            // 1SA, 1FR etc
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=1;BYDAY=1MO',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '1MO',
                ]
            ],

            // 2SU
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=1;BYDAY=2SU',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '2SU',
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYYEARDAY=7,8,9,10,11,12,13',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '2SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYYEARDAY=-300,-299,-298,-297,-296,-295,-294',
                [
                    'BYMONTH' => 3,
                    'BYDAY' => '2SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            // 3SU
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYYEARDAY=-293,-292,-291,-290,-289,-288,-287',
                [
                    'BYMONTH' => 3,
                    'BYDAY' => '3SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            // 4SU
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYYEARDAY=-286,-285,-284,-283,-282,-281,-280',
                [
                    'BYMONTH' => 3,
                    'BYDAY' => '4SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            // -1SU
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYYEARDAY=-283,-282,-281,-280,-279,-278,-277',
                [
                    'BYMONTH' => 3,
                    'BYDAY' => '-1SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYYEARDAY=-314,-313,-312,-311,-310,-309,-308',
                [
                    'BYMONTH' => 2,
                    'BYDAY' => '-1SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYYEARDAY=-8,-7,-6,-5,-4,-3,-2',
                [
                    'BYMONTH' => 12,
                    'BYDAY' => '-1SU',
                    'OFFSET' => '-1SA'
                ]
            ],


            // Specials
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=1;BYMONTHDAY=1',
                [
                    'SPECIAL' => 'newYear',
                ]
            ],
            // TODO: Palm Sunday/Easter are tricky for RRULE

            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=5;BYDAY=1MO',
                [
                    'SPECIAL' => 'mayDay',
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=5;BYDAY=-1MO',
                [
                    'SPECIAL' => 'whitsun',
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=7;BYMONTHDAY=4',
                [
                    'SPECIAL' => 'independence',
                ]
            ],
            [
                // Negative BYYEARDAY to deal w leap years
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SU;BYYEARDAY=-156,-155,-154,-125,-124,-123,-94',
                [
                    'SPECIAL' => '5SU47',
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=8;BYDAY=-1MO',
                [
                    'SPECIAL' => 'summer',
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=11;BYDAY=4TH',
                [
                    'SPECIAL' => 'thanksgiving',
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=12;BYMONTHDAY=25',
                [
                    'SPECIAL' => 'christmas',
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=12;BYMONTHDAY=26',
                [
                    'SPECIAL' => 'boxingDay',
                ]
            ],

            // Multi-day
        ];
    }

    /**
     * @dataProvider happyPath5545DataProvider
     */
    public function testHappyPath5545(string $expectedValue, array $inputValue): void
    {
        $this->assertEquals($expectedValue, $this->rule->rfc5545($inputValue));
    }


    public function RFC5545ReturnsSpecialWithOffsetDataProvider(): array
    {
        return [
            // Before
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYYEARDAY=-7,-6,-5,-4,-3,-2,-1',
                [
                    'SPECIAL' => 'newYear',
                    'OFFSET' => '-1MO',
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=TU;BYYEARDAY=-7,-6,-5,-4,-3,-2,-1',
                [
                    'SPECIAL' => 'newYear',
                    'OFFSET' => '-1TU',
                ]
            ],
            // After
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYYEARDAY=2,3,4,5,6,7,8',
                [
                    'SPECIAL' => 'newYear',
                    'OFFSET' => '1MO',
                ]
            ],
            // May Day
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYYEARDAY=-252,-251,-250,-249,-248,-247,-246',
                [
                    'SPECIAL' => 'mayDay',
                    'OFFSET' => '-1MO',
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=TU;BYYEARDAY=-251,-250,-249,-248,-247,-246,-245',
                [
                    'SPECIAL' => 'mayDay',
                    'OFFSET' => '-1TU',
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SU;BYYEARDAY=-246,-245,-244,-243,-242,-241,-240',
                [
                    'SPECIAL' => 'mayDay',
                    'OFFSET' => '-1SU',
                ]
            ],
            // After
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=TU;BYYEARDAY=-244,-243,-242,-241,-240,-239,-238',
                [
                    'SPECIAL' => 'mayDay',
                    'OFFSET' => '1TU',
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYYEARDAY=-238,-237,-236,-235,-234,-233,-232',
                [
                    'SPECIAL' => 'mayDay',
                    'OFFSET' => '1MO',
                ]
            ],
            // Whitsun
            // -221,-220,-219,-218,-217,-216,-215
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYYEARDAY=-228,-227,-226,-225,-224,-223,-222',
                [
                    'SPECIAL' => 'whitsun',
                    'OFFSET' => '-1MO',
                ]
            ],
            // Independence
            // -181
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYYEARDAY=-188,-187,-186,-185,-184,-183,-182',
                [
                    'SPECIAL' => 'independence',
                    'OFFSET' => '-1MO',
                ]
            ],
            // After
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYYEARDAY=-180,-179,-178,-177,-176,-175,-174',
                [
                    'SPECIAL' => 'independence',
                    'OFFSET' => '1MO',
                ]
            ],
            // First fifth Sunday after the 4th July
            // -156,-155,-154,-125,-124,-123,-94
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYYEARDAY=-157,-156,-155,-126,-125,-124,-95',
                [
                    'SPECIAL' => '5SU47',
                    'OFFSET' => '-1SA',
                ]
            ],
            // After
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SU;BYYEARDAY=-149,-148,-147,-118,-117,-116,-87',
                [
                    'SPECIAL' => '5SU47',
                    'OFFSET' => '1SU',
                ]
            ],
            // Summer bank hol
            // -129,-128,-127,-126,-125,-124,-123
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYYEARDAY=-136,-135,-134,-133,-132,-131,-130',
                [
                    'SPECIAL' => 'summer',
                    'OFFSET' => '-1MO',
                ]
            ],
            // Thanksgiving
            // -40,-39,-38,-37,-36,-35,-34
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=TH;BYYEARDAY=-47,-46,-45,-44,-43,-42,-41',
                [
                    'SPECIAL' => 'thanksgiving',
                    'OFFSET' => '-1TH',
                ]
            ],
            // Christmas
            // -7
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYYEARDAY=-14,-13,-12,-11,-10,-9,-8',
                [
                    'SPECIAL' => 'christmas',
                    'OFFSET' => '-1MO',
                ]
            ],
            // Boxing Day
            // -6
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SU;BYYEARDAY=-5,-4,-3,-2,-1,1,2',
                [
                    'SPECIAL' => 'boxingDay',
                    'OFFSET' => '1SU',
                ]
            ],
        ];
    }

    /**
     * @dataProvider RFC5545ReturnsSpecialWithOffsetDataProvider
     */
    public function test5545ReturnsSpecialWithOffset(string $expectedValue, array $inputValue): void
    {
        $this->assertEquals($expectedValue, $this->rule->rfc5545($inputValue));
    }


    public function calculateOffsetDataProvider(): array
    {
        return [
            // Same week
            [
                -1,
                'TU',
                '-1MO'
            ],
            [
                -2,
                'WE',
                '-1MO'
            ],
            [
                -6,
                'SU',
                '-1MO'
            ],
            // Different week
            [
                -6,
                'MO',
                '-1TU'
            ],
            [
                -1,
                'MO',
                '-1SU'
            ],
            // Positive offset
            [
                1,
                'MO',
                '1TU'
            ],
            [
                6,
                'MO',
                '1SU'
            ],
            // Different week
            [
                6,
                'TU',
                '1MO'
            ],
            [
                1,
                'SU',
                '1MO'
            ],

        ];
    }

    /**
     * @dataProvider calculateOffsetDataProvider
     */
    public function testCalculateOffset(string $expectedValue, string $day, string $offset): void
    {
        $this->assertEquals($expectedValue, $this->rule->calculate_offset_days($day, $offset));
    }

    public function offsetBYYEARDAYDataProvider(): array
    {
        return [
            [
                '1,2,3,4,5,6,7',
                [2,3,4,5,6,7,8],
                -1
            ],
            [
                '-10,-9,-8,-7,-6,-5,-4',
                [-9,-8,-7,-6,-5,-4,-3],
                -1
            ],
            [
                '-1,1,2,3,4,5,6',
                [1,2,3,4,5,6,7],
                -1
            ],
            [
                '-1,1,2,3,4,5,6',
                [7,8,9,10,11,12,13],
                -7
            ],
            [
                '3,4,5,6,7,8,9',
                [2,3,4,5,6,7,8],
                1
            ],
            [
                '-6,-5,-4,-3,-2,-1,1',
                [-7,-6,-5,-4,-3,-2,-1],
                1
            ],
            [
                '-3,-2,-1,1,2,3,4',
                [-7,-6,-5,-4,-3,-2,-1],
                4
            ],


        ];
    }

    /**
    * @dataProvider offsetBYYEARDAYDataProvider
    */
    // public function testOffsetBYYEARDAY(string $expectedValue, array $days, int $offset): void
    // {
    //     $this->assertEquals($expectedValue, $this->rule->offset_byyearday($days, $offset));
    // }
}
