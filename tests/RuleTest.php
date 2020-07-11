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
                    'OFFSET' => 0
                ]
            ],

            [
                'The second Sunday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '2SU',
                    'OFFSET' => 0
                ]
            ],

            [
                'The third Sunday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '3SU',
                    'OFFSET' => 0
                ]
            ],

            [
                'The fourth Sunday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '4SU',
                    'OFFSET' => 0
                ]
            ],

            [
                'The last Sunday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '-1SU',
                    'OFFSET' => 0
                ]
            ],

            [
                'The first Sunday in June',
                [
                    'BYMONTH' => 6,
                    'BYDAY' => '1SU',
                    'OFFSET' => 0
                ]
            ],

            [
                'The first Sunday in July',
                [
                    'BYMONTH' => 7,
                    'BYDAY' => '1SU',
                    'OFFSET' => 0
                ]
            ],

            [
                'The first Sunday in August',
                [
                    'BYMONTH' => 8,
                    'BYDAY' => '1SU',
                    'OFFSET' => 0
                ]
            ],

            [
                'The last Sunday in August',
                [
                    'BYMONTH' => 8,
                    'BYDAY' => '-1SU',
                    'OFFSET' => 0
                ]
            ],

            [
                'The first Saturday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SA',
                    'OFFSET' => 0
                ]
            ],

            [
                'The first Monday in January',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '1MO',
                    'OFFSET' => 0
                ]
            ],

            [
                'The last Tuesday in January',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '-1TU',
                    'OFFSET' => 0
                ]
            ],

            [
                'The Saturday before the first Sunday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                    'OFFSET' => -1
                ]
            ],

            [
                'The Sunday before the first Monday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1MO',
                    'OFFSET' => -1
                ]
            ],

            [
                'The Monday before the first Tuesday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1TU',
                    'OFFSET' => -1
                ]
            ],

            [
                'The Friday before the first Sunday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                    'OFFSET' => -2
                ]
            ],

            [
                'The Thursday before the first Sunday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                    'OFFSET' => -3
                ]
            ],

            [
                'The first Sunday in May',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
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
                'New Year\'s day',
                [
                    'SPECIAL' => 'newYear',
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
                'BYMONTH' => 5,
                'BYDAY' => '1SU',
                'OFFSET' => -7
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
                'OFFSET' => 1.4
            ]],
            // Fine in year but not in month
            // [['BYMONTH' => 5,
            // 'BYDAY' => '6SU',
            // ]],
            // Not valid for Sacred Harp
            // [['BYMONTH' => 5,
            // 'BYDAY' => '-2SU',
            // ]],
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
                    'OFFSET' => 0
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=5;BYDAY=2SU',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '2SU',
                    'OFFSET' => 0
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=5;BYDAY=3SU',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '3SU',
                    'OFFSET' => 0
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=5;BYDAY=4SU',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '4SU',
                    'OFFSET' => 0
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=5;BYDAY=-1SU',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '-1SU',
                    'OFFSET' => 0
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=6;BYDAY=1SU',
                [
                    'BYMONTH' => 6,
                    'BYDAY' => '1SU',
                    'OFFSET' => 0
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=7;BYDAY=1SU',
                [
                    'BYMONTH' => 7,
                    'BYDAY' => '1SU',
                    'OFFSET' => 0
                ]
            ],
            // Nth day rules
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=1;BYDAY=1SA',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '1SA',
                    'OFFSET' => 0
                ]
            ],
            // Sat before first Sunday
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYYEARDAY=-1,1,2,3,4,5,6',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '1SU',
                    'OFFSET' => -1
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYYEARDAY=31,32,33,34,35,36,37',
                [
                    'BYMONTH' => 2,
                    'BYDAY' => '1SU',
                    'OFFSET' => -1
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=59,60,61,62,63,64,65,66',
                [
                    'BYMONTH' => 3,
                    'BYDAY' => '1SU',
                    'OFFSET' => -1
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=90,91,92,93,94,95,96,97',
                [
                    'BYMONTH' => 4,
                    'BYDAY' => '1SU',
                    'OFFSET' => -1
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=120,121,122,123,124,125,126,127',
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                    'OFFSET' => -1
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=151,152,153,154,155,156,157,158',
                [
                    'BYMONTH' => 6,
                    'BYDAY' => '1SU',
                    'OFFSET' => -1
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=181,182,183,184,185,186,187,188',
                [
                    'BYMONTH' => 7,
                    'BYDAY' => '1SU',
                    'OFFSET' => -1
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=212,213,214,215,216,217,218,219',
                [
                    'BYMONTH' => 8,
                    'BYDAY' => '1SU',
                    'OFFSET' => -1
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=243,244,245,246,247,248,249,250',
                [
                    'BYMONTH' => 9,
                    'BYDAY' => '1SU',
                    'OFFSET' => -1
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=273,274,275,276,277,278,279,280',
                [
                    'BYMONTH' => 10,
                    'BYDAY' => '1SU',
                    'OFFSET' => -1
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=304,305,306,307,308,309,310,311',
                [
                    'BYMONTH' => 11,
                    'BYDAY' => '1SU',
                    'OFFSET' => -1
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYMONTHDAY=-1,1,2,3,4,5,6;BYYEARDAY=334,335,336,337,338,339,340,341',
                [
                    'BYMONTH' => 12,
                    'BYDAY' => '1SU',
                    'OFFSET' => -1
                ]
            ],
            // Friday before first Sun
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=FR;BYYEARDAY=-2,-1,1,2,3,4,5',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '1SU',
                    'OFFSET' => -2
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=FR;BYYEARDAY=30,31,32,33,34,35,36',
                [
                    'BYMONTH' => 2,
                    'BYDAY' => '1SU',
                    'OFFSET' => -2
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=FR;BYMONTHDAY=-2,-1,1,2,3,4,5;BYYEARDAY=58,59,60,61,62,63,64,65',
                [
                    'BYMONTH' => 3,
                    'BYDAY' => '1SU',
                    'OFFSET' => -2
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=FR;BYMONTHDAY=-2,-1,1,2,3,4,5;BYYEARDAY=333,334,335,336,337,338,339,340',
                [
                    'BYMONTH' => 12,
                    'BYDAY' => '1SU',
                    'OFFSET' => -2
                ]
            ],
            // Monday before first Sun
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYYEARDAY=-6,-5,-4,-3,-2,-1,1',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '1SU',
                    'OFFSET' => -6
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYYEARDAY=26,27,28,29,30,31,32',
                [
                    'BYMONTH' => 2,
                    'BYDAY' => '1SU',
                    'OFFSET' => -6
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYMONTHDAY=-6,-5,-4,-3,-2,-1,1;BYYEARDAY=54,55,56,57,58,59,60,61',
                [
                    'BYMONTH' => 3,
                    'BYDAY' => '1SU',
                    'OFFSET' => -6
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYMONTHDAY=-6,-5,-4,-3,-2,-1,1;BYYEARDAY=329,330,331,332,333,334,335,336',
                [
                    'BYMONTH' => 12,
                    'BYDAY' => '1SU',
                    'OFFSET' => -6
                ]
            ],
            // Positive offsets
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYYEARDAY=2,3,4,5,6,7,8',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '1SU',
                    'OFFSET' => 1
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=MO;BYMONTHDAY=2,3,4,5,6,7,8;BYYEARDAY=61,62,63,64,65,66,67,68',
                [
                    'BYMONTH' => 3,
                    'BYDAY' => '1SU',
                    'OFFSET' => 1
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=TU;BYYEARDAY=3,4,5,6,7,8,9',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '1SU',
                    'OFFSET' => 2
                ]
            ],
            [
                'FREQ=YEARLY;INTERVAL=1;BYDAY=SA;BYYEARDAY=7,8,9,10,11,12,13',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '1SU',
                    'OFFSET' => 6
                ]
            ],

            // 1SA, 1FR etc
            [
                'FREQ=YEARLY;INTERVAL=1;BYMONTH=1;BYDAY=1MO',
                [
                    'BYMONTH' => 1,
                    'BYDAY' => '1MO',
                    'OFFSET' => 0
                ]
            ],


            // Specials

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


    /**
     * Uses same invalid data for all fns
     * @dataProvider invalidData
     */
    public function testThrowsException5545(array $inputValue): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->rule->rfc5545($inputValue);
    }
}
