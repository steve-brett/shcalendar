<?php

declare(strict_types=1);

namespace SacredHarpCalendar\Test;

use SacredHarpCalendar\RuleCreator;
use PHPUnit\Framework\TestCase;

class RuleCreatorTest extends TestCase # Has to be [ClassName]Test
{
    /**
     * @var RuleCreator
     */
    private $rule;

    protected function setUp() :void
    {
        $this->rule = new RuleCreator();
    }

    public static function happyPathSundayDataProvider(): array
    {
        return [
            [
                [
                    'TYPE' => 'NTHDAY',
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU'
                ],
                '2019-05-05'
            ],

            [
                [
                    'TYPE' => 'NTHDAY',
                    'BYMONTH' => 5,
                    'BYDAY' => '2SU'
                ],
                '2019-05-12'
            ],

            [
                [
                    'TYPE' => 'NTHDAY',
                    'BYMONTH' => 5,
                    'BYDAY' => '3SU'
                ],
                '2019-05-19'
            ],

            [
                [
                    'TYPE' => 'NTHDAY',
                    'BYMONTH' => 6,
                    'BYDAY' => '1SU'
                ],
                '2019-06-02'
            ],

            [
                [
                    'TYPE' => 'NTHDAY',
                    'BYMONTH' => 7,
                    'BYDAY' => '1SU'
                ],
                '2019-07-07'
            ],


        ];
    }

    /**
     * @dataProvider happyPathSundayDataProvider
     */
    public function testHappyPathSunday(array $expectedValue, string $inputValue): void
    {
        $this->assertEquals($expectedValue, $this->rule->nthDay(\DateTime::createFromFormat('!Y-m-d', $inputValue)));
    }


    public static function happyPathSundayOffsetDataProvider(): array
    {
        return [
            // Saturday before first sunday
            [
                [
                    'TYPE' => 'NTHDAY',
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA'
                ],
                [
                    'date' => '2019-05-04',
                    'refday' => 'Sun'
                ]
            ],
            // Saturday before nth sunday
            [
                [
                    'TYPE' => 'NTHDAY',
                    'BYMONTH' => 5,
                    'BYDAY' => '2SU',
                    'OFFSET' => '-1SA'
                ],
                [
                    'date' => '2019-05-11',
                    'refday' => 'Sun'
                ]
            ],
            // First sunday is 1st of the month
            [
                [
                    'TYPE' => 'NTHDAY',
                    'BYMONTH' => 7,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA'
                ],
                [
                    'date' => '2018-06-30',
                    'refday' => 'Sun'
                ]
            ],
            // First Sunday is 1st Jan
            [
                [
                    'TYPE' => 'NTHDAY',
                    'BYMONTH' => 1,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1SA'
                ],
                [
                    'date' => '2016-12-31',
                    'refday' => 'Sun'
                ]
            ],
            // Friday before first Sunday
            [
                [
                    'TYPE' => 'NTHDAY',
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1FR'
                ],
                [
                    'date' => '2019-05-03',
                    'refday' => 'Sun'
                ]
            ],
            // Six days before first Sunday
            [
                [
                    'TYPE' => 'NTHDAY',
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU',
                    'OFFSET' => '-1MO'
                ],
                [
                    'date' => '2019-04-29',
                    'refday' => 'Sun'
                ]
            ],

        ];
    }

    /**
     * @dataProvider happyPathSundayOffsetDataProvider
     */
    public function testHappyPathSundayOffset(array $expectedValue, array $inputValue): void
    {
        $this->assertEquals($expectedValue, $this->rule->nthDay(\DateTime::createFromFormat('!Y-m-d', $inputValue['date']), null, $inputValue['refday']));
    }

    public static function happyPathDayDataProvider(): array
    {
        return [
            // First Saturday
            [
                [
                    'TYPE' => 'NTHDAY',
                    'BYMONTH' => 5,
                    'BYDAY' => '1SA'
                ],
                '2019-05-04'
            ],

            // Second Saturday
            [
                [
                    'TYPE' => 'NTHDAY',
                    'BYMONTH' => 5,
                    'BYDAY' => '2SA'
                ],
                '2019-05-11'
            ],

            // Third Saturday
            [
                [
                    'TYPE' => 'NTHDAY',
                    'BYMONTH' => 5,
                    'BYDAY' => '3SA'
                ],
                '2019-05-18'
            ],

            // Fourth Saturday
            [
                [
                    'TYPE' => 'NTHDAY',
                    'BYMONTH' => 5,
                    'BYDAY' => '4SA'
                ],
                '2019-05-25'
            ],

            // Different month
            [
                [
                    'TYPE' => 'NTHDAY',
                    'BYMONTH' => 6,
                    'BYDAY' => '1SA'
                ],
                '2019-06-01'
            ],

            // First Monday
            // TODO Unnecessary?
            [
                [
                    'TYPE' => 'NTHDAY',
                    'BYMONTH' => 6,
                    'BYDAY' => '1MO'
                ],
                '2019-06-03'
            ],

        ];
    }

    /**
     * @dataProvider happyPathDayDataProvider
     */
    public function testHappyPathDay(array $expectedValue, string $inputValue): void
    {
        $this->assertEquals($expectedValue, $this->rule->nthDay(\DateTime::createFromFormat('!Y-m-d', $inputValue)));
    }

    public static function invalidData(): array
    {
        return [
            // Before 1800
            ['1799-12-31'],
            ['2019-06-30'],
        ];
    }

    /**
     * @dataProvider invalidData
     */
    public function testThrowsException(string $inputValue): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->rule->nthDay(\DateTime::createFromFormat('Y-m-d', $inputValue));
    }

    public static function invalidDataTwo(): array
    {
        return [
            [[
                'date' => '2019-06-29',
                'refday' => 'Sat'
            ]],
            [[
                'date' => '2019-06-24',
                'refday' => 'Sun'
            ]],
            [[
                'date' => '2019-07-29',
                'refday' => 'Mon'
            ]],
            [[
                'date' => '2019-07-01',
                'refday' => 'Nonsense'
            ]],
            [[
                'date' => '2019-07-01',
                'refday' => 'More nonsense'
            ]],
        ];
    }

    /**
     * @dataProvider invalidDataTwo
     */
    public function testTwoThrowsException(array $inputValue): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->rule->nthDay(\DateTime::createFromFormat('!Y-m-d', $inputValue['date']), null, $inputValue['refday']);
    }

    /**
     * Last day tests -----------------------------------------------
     */

    public static function happyPathLastSundayDataProvider(): array
    {
        return [
            [[
                'TYPE' => 'LASTDAY',
                'BYMONTH' => 5,
                'BYDAY' => '-1SU'
            ], '2019-05-26'],

            [[
                'TYPE' => 'LASTDAY',
                'BYMONTH' => 6,
                'BYDAY' => '-1SU'
            ], '2019-06-30'],

            [[
                'TYPE' => 'LASTDAY',
                'BYMONTH' => 6,
                'BYDAY' => '-1SA'
            ], '2019-06-29'],
        ];
    }

    /**
     * @dataProvider happyPathLastSundayDataProvider
     */
    public function testHappyPathLastSunday(array $expectedValue, string $inputValue): void
    {
        $this->assertEquals($expectedValue, $this->rule->lastDay(\DateTime::createFromFormat('!Y-m-d', $inputValue)));
    }

    public static function happyPathLastSundayOffsetDataProvider(): array
    {
        return [
            [
                [
                    'TYPE' => 'LASTDAY',
                    'BYMONTH' => 5,
                    'BYDAY' => '-1SU',
                    'OFFSET' => '-1SA'
                ],
                [
                    'date' => '2019-05-25',
                    'refday' => 'Sun'
                ]
            ],

        ];
    }

    /**
     * @dataProvider happyPathLastSundayOffsetDataProvider
     */
    public function testHappyPathLastSundayOffset(array $expectedValue, array $inputValue): void
    {
        $this->assertEquals($expectedValue, $this->rule->lastDay(\DateTime::createFromFormat('!Y-m-d', $inputValue['date']), null, $inputValue['refday']));
    }


    public static function invalidDataLastDay(): array
    {
        return [
            ['1799-12-31'],
            ['2019-05-19'],
            ['2019-06-02'],
        ];
    }

    /**
     * @dataProvider invalidDataLastDay
     */
    public function testLastDayThrowsException(string $inputValue): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->rule->lastDay(\DateTime::createFromFormat('Y-m-d', $inputValue));
    }


    /**
     * Special day tests -----------------------------------------------
     */
    public static function happyPathSpecialDataProvider(): array
    {
        return [
            [
                [
                    [
                        'TYPE' => 'SPECIAL',
                        'SPECIAL' => 'newYear'
                    ],
                ],
                '2019-01-01'
            ],

            [
                [
                    [
                        'TYPE' => 'SPECIAL',
                        'SPECIAL' => 'palmSunday'
                    ]
                ],
                '2019-04-14'
            ],

            [
                [
                    [
                        'TYPE' => 'SPECIAL',
                        'SPECIAL' => 'easter'
                    ]
                ],
                '2019-04-21'
            ],

            [
                [[
                    'TYPE' => 'SPECIAL',
                    'SPECIAL' => 'independence',
                ]],
                '2019-07-04'
            ],

            [
                [[
                    'TYPE' => 'SPECIAL',
                    'SPECIAL' => 'independence',
                ]],
                '2020-07-04'
            ],

            [
                [[
                    'TYPE' => 'SPECIAL',
                    'SPECIAL' => 'independence',
                    'OFFSET' => '-1SA',
                ]],
                '2021-07-03'
            ],

            [
                [[
                    'TYPE' => 'SPECIAL',
                    'SPECIAL' => 'independence',
                    'OFFSET' => '-1SU',
                ]],
                '2021-06-27'
            ],

            [
                [[
                    'TYPE' => 'SPECIAL',
                    'SPECIAL' => 'whitsun',
                    'OFFSET' => '1SA',
                ]],
                '2019-06-01'
            ],

        ];
    }

    /**
     * @dataProvider happyPathSpecialDataProvider
     */
    public function testHappyPathSpecial(array $expectedValue, string $inputValue): void
    {
        $this->assertEquals($expectedValue, $this->rule->special(\DateTime::createFromFormat('!Y-m-d', $inputValue, new \DateTimeZone('UTC'))));
    }

    public static function invalidDataSpecial(): array
    {
        return [
            // before 1800
            ['1799-12-31'],
            ['2019-09-15']
        ];
    }

    /**
     * @dataProvider invalidDataSpecial
     */
    public function testSpecialThrowsException(string $inputValue): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->rule->special(\DateTime::createFromFormat('Y-m-d', $inputValue));
    }

    public static function happyPathSpanDataProvider(): array
    {
        return [
            [
                [
                    'DATE' => \DateTime::createFromFormat(
                        \DateTime::ATOM,
                        '2019-09-15T00:00:00+00:00'
                    ),
                    'STARTOFFSET' => -1
                ],
                [
                    'start' => '2019-09-14T15:52:01+00:00',
                    'end' => '2019-09-15T15:52:01+00:00'
                ]
            ],

            [
                [
                    'DATE' => \DateTime::createFromFormat(
                        \DateTime::ATOM,
                        '2019-06-15T00:00:00+00:00'
                    ),
                    'STARTOFFSET' => -1
                ],
                [
                    'start' => '2019-06-14T15:52:01+00:00',
                    'end' => '2019-06-15T15:52:01+00:00'
                ]
            ],

            [
                [
                    'DATE' => \DateTime::createFromFormat(
                        \DateTime::ATOM,
                        '2019-06-15T00:00:00+00:00'
                    ),
                    'STARTOFFSET' => -1
                ],
                [
                    'start' => '2019-06-15T15:52:01+00:00',
                    'end' => '2019-06-14T15:52:01+00:00'
                ]
            ],

            [
                [
                    'DATE' => \DateTime::createFromFormat(
                        \DateTime::ATOM,
                        '2019-06-15T00:00:00+00:00'
                    ),
                    'STARTOFFSET' => -2
                ],
                [
                    'start' => '2019-06-15T15:52:01+00:00',
                    'end' => '2019-06-13T15:52:01+00:00'
                ]
            ],

            [
                [
                    'DATE' => \DateTime::createFromFormat(
                        \DateTime::ATOM,
                        '2019-10-27T00:00:00+00:00'
                    ),
                    'STARTOFFSET' => -1
                ],
                [
                    'start' => '2019-10-26T10:52:01+01:00',
                    'end' => '2019-10-27T17:52:01+00:00'
                ]
            ],
        ];
    }

    /**
     * @dataProvider happyPathSpanDataProvider
     */
    public function testHappyPathSpan(array $expectedValue, array $inputValue): void
    {
        $this->assertEquals($expectedValue, $this->rule->span(\DateTime::createFromFormat(\DateTime::ATOM, $inputValue['start']), \DateTime::createFromFormat(\DateTime::ATOM, $inputValue['end'])));
    }

    public static function happyPathSpanSingleDataProvider(): array
    {
        return [
            [
                ['DATE' => \DateTime::createFromFormat(
                    \DateTime::ATOM,
                    '2019-09-15T00:00:00+00:00'
                ),],
                '2019-09-15T15:52:01+00:00'
            ],
        ];
    }

    /**
     * @dataProvider happyPathSpanSingleDataProvider
     */
    public function testHappyPathSpanSingle(array $expectedValue, string $inputValue): void
    {
        $this->assertEquals($expectedValue, $this->rule->span(\DateTime::createFromFormat(\DateTime::ATOM, $inputValue)));
    }

    public static function invalidDataSpan(): array
    {
        return [
            [[
                'start' => '2005-08-01T15:52:01+00:00',
                'end' => '2005-08-08T15:52:01+00:00'
            ]],
            [[
                'start' => '2019-05-01T15:52:01+03:00',
                'end' => '2019-05-11T15:52:01+03:00'
            ]],

        ];
    }

    /**
     * @dataProvider invalidDataSpan
     */
    public function testSpanThrowsException(array $inputValue): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->rule->span(\DateTime::createFromFormat(\DateTime::ATOM, $inputValue['start']), \DateTime::createFromFormat(\DateTime::ATOM, $inputValue['end']));
    }

    public static function happyPathCreateDataProvider(): array
    {
        return [
            [
                [
                    [
                        'TYPE' => 'NTHDAY',
                        'BYMONTH' => 5,
                        'BYDAY' => '2SU',
                        'OFFSET' => '-1SA',
                    ],
                    [
                        'TYPE' => 'NTHDAY',
                        'BYMONTH' => 5,
                        'BYDAY' => '2SA'
                    ],
                    [
                        'TYPE' => 'SPECIAL',
                        'SPECIAL' => 'mayDay',
                        'OFFSET' => '1SA'
                    ],
                ],
                [
                    'start' => '2019-05-11T10:30:00+00:00',
                    'end' => '2019-05-11T16:00:00+00:00'
                ]
            ],

            [
                [
                    [
                        'TYPE' => 'NTHDAY',
                        'BYMONTH' => 5,
                        'BYDAY' => '4SU',
                        'OFFSET' => '-1SA'
                    ],
                    [
                        'TYPE' => 'LASTDAY',
                        'BYMONTH' => 5,
                        'BYDAY' => '-1SU',
                        'OFFSET' => '-1SA'
                    ],
                    [
                        'TYPE' => 'NTHDAY',
                        'BYMONTH' => 5,
                        'BYDAY' => '4SA'
                    ],
                    [
                        'TYPE' => 'LASTDAY',
                        'BYMONTH' => 5,
                        'BYDAY' => '-1SA'
                    ],
                    [
                        'TYPE' => 'SPECIAL',
                        'SPECIAL' => 'whitsun',
                        'OFFSET' => '-1SA'
                    ],
                ],
                [
                    'start' => '2019-05-25T10:30:00+00:00',
                    'end' => '2019-05-25T16:00:00+00:00'
                ]
            ],

            [
                [
                    [
                        'TYPE' => 'LASTDAY',
                        'BYMONTH' => 6,
                        'BYDAY' => '-1SU',
                        'OFFSET' => '-1SA'
                    ],
                    [
                        'TYPE' => 'LASTDAY',
                        'BYMONTH' => 6,
                        'BYDAY' => '-1SA'
                    ],
                    [
                        'TYPE' => 'SPECIAL',
                        'SPECIAL' => 'independence',
                        'OFFSET' => '-1SA'
                    ],
                ],
                [
                    'start' => '2019-06-29T10:30:00+00:00',
                    'end' => '2019-06-29T16:00:00+00:00'
                ]
            ],

            [
                [
                    [
                        'TYPE' => 'NTHDAY',
                        'BYMONTH' => 5,
                        'BYDAY' => '2SU'
                    ],
                    [
                        'TYPE' => 'SPECIAL',
                        'SPECIAL' => 'mayDay',
                        'OFFSET' => '1SU'
                    ],
                ],
                [
                    'start' => '2019-05-12T10:30:00+00:00',
                    'end' => '2019-05-12T16:00:00+00:00'
                ]
            ],

            [
                [
                    [
                        'TYPE' => 'NTHDAY',
                        'BYMONTH' => 5,
                        'BYDAY' => '4SU'
                    ],
                    [
                        'TYPE' => 'LASTDAY',
                        'BYMONTH' => 5,
                        'BYDAY' => '-1SU'
                    ],
                    [
                        'TYPE' => 'SPECIAL',
                        'SPECIAL' => 'whitsun',
                        'OFFSET' => '-1SU'
                    ],
                ],
                [
                    'start' => '2019-05-26T10:30:00+00:00',
                    'end' => '2019-05-26T16:00:00+00:00'
                ]
            ],

            [
                [
                    [
                        'TYPE' => 'NTHDAY',
                        'BYMONTH' => 9,
                        'BYDAY' => '3SU',
                        'STARTOFFSET' => -1
                    ],
                ],
                [
                    'start' => '2019-09-14T10:30:00+00:00',
                    'end' => '2019-09-15T16:00:00+00:00'
                ]
            ],

            [
                [
                    [
                        'TYPE' => 'NTHDAY',
                        'BYMONTH' => 5,
                        'BYDAY' => '4SU',
                        'STARTOFFSET' => -1
                    ],
                    [
                        'TYPE' => 'LASTDAY',
                        'BYMONTH' => 5,
                        'BYDAY' => '-1SU',
                        'STARTOFFSET' => -1
                    ],
                    [
                        'TYPE' => 'SPECIAL',
                        'SPECIAL' => 'whitsun',
                        'OFFSET' => '-1SU',
                        'STARTOFFSET' => -1
                    ],
                ],
                [
                    'start' => '2019-05-25T10:30:00+00:00',
                    'end' => '2019-05-26T16:00:00+00:00'
                ]
            ],

            [
                [
                    [
                        'TYPE' => 'NTHDAY',
                        'BYMONTH' => 5,
                        'BYDAY' => '4SU',
                        'OFFSET' => '-1FR',
                        'STARTOFFSET' => -1
                    ],
                    [
                        'TYPE' => 'LASTDAY',
                        'BYMONTH' => 5,
                        'BYDAY' => '-1SU',
                        'OFFSET' => '-1FR',
                        'STARTOFFSET' => -1
                    ],
                    [
                        'TYPE' => 'NTHDAY',
                        'BYMONTH' => 5,
                        'BYDAY' => '4FR',
                        'STARTOFFSET' => -1
                    ],
                    [
                        'TYPE' => 'SPECIAL',
                        'SPECIAL' => 'whitsun',
                        'OFFSET' => '-1FR',
                        'STARTOFFSET' => -1
                    ],
                ],
                [
                    'start' => '2019-05-23T10:30:00+00:00',
                    'end' => '2019-05-24T16:00:00+00:00'
                ]
            ],

            [
                [
                    [
                        'TYPE' => 'NTHDAY',
                        'BYMONTH' => 6,
                        'BYDAY' => '1SU',
                        'OFFSET' => '-1FR',
                        'STARTOFFSET' => -2
                    ],
                    [
                        'TYPE' => 'LASTDAY',
                        'BYMONTH' => 5,
                        'BYDAY' => '-1FR',
                        'STARTOFFSET' => -2
                    ],
                    [
                        'TYPE' => 'SPECIAL',
                        'SPECIAL' => 'whitsun',
                        'OFFSET' => '1FR',
                        'STARTOFFSET' => -2
                    ],
                ],
                [
                    'start' => '2019-05-29T10:30:00+00:00',
                    'end' => '2019-05-31T16:00:00+00:00'
                ]
            ],

            [
                [

                    [
                        'TYPE' => 'LASTDAY',
                        'BYMONTH' => 10,
                        'BYDAY' => '-1SU',
                    ],
                    [
                        'TYPE' => 'SPECIAL',
                        'SPECIAL' => 'L5SUT',
                    ],
                    [
                        'TYPE' => 'SPECIAL',
                        'SPECIAL' => '5SULabour',
                    ],
                    [
                        'TYPE' => 'SPECIAL',
                        'SPECIAL' => 'L5SU',
                    ],
                    [
                        'TYPE' => 'SPECIAL',
                        'SPECIAL' => 'londonChristianHarmony',
                    ],
                ],
                [
                    'start' => '2022-10-30T10:30:00+00:00',
                    'end' => '2022-10-30T16:00:00+00:00'
                ]
            ],

        ];
    }

    /**
     * @dataProvider happyPathCreateDataProvider
     */
    public function testHappyPathCreate(array $expectedValue, array $inputValue): void
    {
        $this->assertEquals($expectedValue, $this->rule->create(\DateTime::createFromFormat(\DateTime::ATOM, $inputValue['start']), \DateTime::createFromFormat(\DateTime::ATOM, $inputValue['end'])));
    }

    public function testYMDtoDate(): void
    {
        $this->assertEquals(['christmas' => \DateTime::createFromFormat('!Y-m-d', '2019-12-25', new \DateTimeZone('UTC'))], $this->rule->ymdToDatetime(['christmas' => '2019-12-25']));
    }
}
