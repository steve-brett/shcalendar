<?php

declare(strict_types=1);

namespace SHCalendar\Test;

use SHCalendar\Rule;
use PHPUnit\Framework\TestCase;

class RuleTest extends TestCase  # Has to be [ClassName]Test
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
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=59,60,61,62,63,64,65,66',
                [
                    'BYMONTH' => 3,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=90,91,92,93,94,95,96,97',
                [
                    'BYMONTH' => 4,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=120,121,122,123,124,125,126,127',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=151,152,153,154,155,156,157,158',
                [
                    'BYMONTH' => 6,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=181,182,183,184,185,186,187,188',
                [
                    'BYMONTH' => 7,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=212,213,214,215,216,217,218,219',
                [
                    'BYMONTH' => 8,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=243,244,245,246,247,248,249,250',
                [
                    'BYMONTH' => 9,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=273,274,275,276,277,278,279,280',
                [
                    'BYMONTH' => 10,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=304,305,306,307,308,309,310,311',
                [
                    'BYMONTH' => 11,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=334,335,336,337,338,339,340,341',
                [
                    'BYMONTH' => 12,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA'
                ]
            ],
            // Friday before first Sun
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=FR;BYYEARDAY=-2,-1,1,2,3,4,5',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1FR'
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=FR;BYYEARDAY=30,31,32,33,34,35,36',
                [
                    'BYMONTH' => 2,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1FR'
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=FR;BYMONTHDAY=-2,-1,1,2,3,4,5;BYYEARDAY=58,59,60,61,62,63,64,65',
                [
                    'BYMONTH' => 3,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1FR'
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=FR;BYMONTHDAY=-2,-1,1,2,3,4,5;BYYEARDAY=333,334,335,336,337,338,339,340',
                [
                    'BYMONTH' => 12,
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
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYMONTHDAY=-6,-5,-4,-3,-2,-1,1;BYYEARDAY=54,55,56,57,58,59,60,61',
                [
                    'BYMONTH' => 3,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1MO'
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYMONTHDAY=-6,-5,-4,-3,-2,-1,1;BYYEARDAY=329,330,331,332,333,334,335,336',
                [
                    'BYMONTH' => 12,
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
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYMONTHDAY=2,3,4,5,6,7,8;BYYEARDAY=61,62,63,64,65,66,67,68',
                [
                    'BYMONTH' => 3,
                    'BYDAY' => '1SU',
                    'OFFSET' => '1MO'
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=TU;BYYEARDAY=3,4,5,6,7,8,9',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '1SU',
                    'OFFSET' => '1TU'
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYYEARDAY=7,8,9,10,11,12,13',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '1SU',
                    'OFFSET' => '1SA'
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


    /**
     * Uses same invalid data for all fns
     * @dataProvider invalidData
     */
    public function testThrowsException5545(array $inputValue): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->rule->rfc5545($inputValue);
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
}
