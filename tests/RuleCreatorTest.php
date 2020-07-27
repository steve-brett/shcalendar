<?php

declare(strict_types=1);

namespace SHCalendar\Test;

use SHCalendar\RuleCreator;
use PHPUnit\Framework\TestCase;

class RuleCreatorTest extends TestCase  # Has to be [ClassName]Test
{
    /**
     * @var RuleCreator
     */
    private $rule;

    protected function setUp()
    {
        $this->rule = new RuleCreator();
    }

    public function happyPathSundayDataProvider(): array
    {
        return [
            [
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SU'
                ],
                '2019-05-05'
            ],

            [
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '2SU'
                ],
                '2019-05-12'
            ],

            [
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '3SU'
                ],
                '2019-05-19'
            ],

            [
                [
                    'BYMONTH' => 6,
                    'BYDAY' => '1SU'
                ],
                '2019-06-02'
            ],

            [
                [
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


    public function happyPathSundayOffsetDataProvider(): array
    {
        return [
            // Saturday before first sunday
            [
                [
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
        $this->assertEquals($expectedValue, $this->rule->nthDay(\DateTime::createFromFormat('!Y-m-d', $inputValue['date']), $inputValue['refday']));
    }

    public function happyPathDayDataProvider(): array
    {
        return [
            // First Saturday
            [
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '1SA'
                ],
                '2019-05-04'
            ],

            // Second Saturday
            [
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '2SA'
                ],
                '2019-05-11'
            ],

            // Third Saturday
            [
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '3SA'
                ],
                '2019-05-18'
            ],

            // Fourth Saturday
            [
                [
                    'BYMONTH' => 5,
                    'BYDAY' => '4SA'
                ],
                '2019-05-25'
            ],

            // Different month
            [
                [
                    'BYMONTH' => 6,
                    'BYDAY' => '1SA'
                ],
                '2019-06-01'
            ],

            // First Monday
            // TODO Unnecessary?
            [
                [
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

    public function invalidData(): array
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

    public function invalidDataTwo(): array
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
        $this->rule->nthDay(\DateTime::createFromFormat('!Y-m-d', $inputValue['date']), $inputValue['refday']);
    }

    /**
     * Last day tests -----------------------------------------------
     */

    public function happyPathLastSundayDataProvider(): array
    {
        return [
            [[
                'BYMONTH' => 5,
                'BYDAY' => '-1SU'
            ], '2019-05-26'],

            [[
                'BYMONTH' => 6,
                'BYDAY' => '-1SU'
            ], '2019-06-30'],

            [[
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

    public function happyPathLastSundayOffsetDataProvider(): array
    {
        return [
            [
                [
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
        $this->assertEquals($expectedValue, $this->rule->lastDay(\DateTime::createFromFormat('!Y-m-d', $inputValue['date']), $inputValue['refday']));
    }


    public function invalidDataLastDay(): array
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
    // TODO: change offset to -MO, SU etc 
    public function happyPathSpecialDataProvider(): array
    {
        return [
            [['SPECIAL' => 'newYear'], '2019-01-01'],

            [['SPECIAL' => 'palmSunday'], '2019-04-14'],

            [['SPECIAL' => 'easter'], '2019-04-21'],

            [['SPECIAL' => 'independence'], '2019-07-04'],

            [['SPECIAL' => 'independence'], '2020-07-04'],

            [[
                'SPECIAL' => 'independence',
                'OFFSET' => '-1SA',
            ], '2021-07-03'],

            [[
                'SPECIAL' => 'independence',
                'OFFSET' => '-1SU',
            ], '2021-06-27'],

            [[
                'SPECIAL' => 'whitsun',
                'OFFSET' => '1SA',
            ], '2019-06-01'],

        ];
    }

    /**
     * @dataProvider happyPathSpecialDataProvider
     */
    public function testHappyPathSpecial(array $expectedValue, string $inputValue): void
    {
        $this->assertEquals($expectedValue, $this->rule->special(\DateTime::createFromFormat('!Y-m-d', $inputValue, new \DateTimeZone('UTC'))));
    }

    public function invalidDataSpecial(): array
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

    public function happyPathSpanDataProvider(): array
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

    public function happyPathSpanSingleDataProvider(): array
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

    public function invalidDataSpan(): array
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

    public function happyPathCreateDataProvider(): array
    {
        return [
            [
                [
                    'NTHSUN' => [
                        'BYMONTH' => 5,
                        'BYDAY' => '2SU',
                        'OFFSET' => '-1SA',
                    ],
                    'NTHDAY' => [
                        'BYMONTH' => 5,
                        'BYDAY' => '2SA'
                    ],
                    'SPECIAL' => [
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
                    'NTHSUN' => [
                        'BYMONTH' => 5,
                        'BYDAY' => '4SU',
                        'OFFSET' => '-1SA'
                    ],
                    'NTHDAY' => [
                        'BYMONTH' => 5,
                        'BYDAY' => '4SA'
                    ],
                    'LASTSUN' => [
                        'BYMONTH' => 5,
                        'BYDAY' => '-1SU',
                        'OFFSET' => '-1SA'
                    ],
                    'LASTDAY' => [
                        'BYMONTH' => 5,
                        'BYDAY' => '-1SA'
                    ],
                    'SPECIAL' => [
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
                    'LASTSUN' => [
                        'BYMONTH' => 6,
                        'BYDAY' => '-1SU',
                        'OFFSET' => '-1SA'
                    ],
                    'LASTDAY' => [
                        'BYMONTH' => 6,
                        'BYDAY' => '-1SA'
                    ],
                    'SPECIAL' => [
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
                    'NTHSUN' => [
                        'BYMONTH' => 5,
                        'BYDAY' => '2SU'
                    ],
                    'SPECIAL' => [
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
                    'NTHSUN' => [
                        'BYMONTH' => 5,
                        'BYDAY' => '4SU'
                    ],
                    'LASTSUN' => [
                        'BYMONTH' => 5,
                        'BYDAY' => '-1SU'
                    ],
                    'SPECIAL' => [
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
                    'NTHSUN' => [
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
                    'NTHSUN' => [
                        'BYMONTH' => 5,
                        'BYDAY' => '4SU',
                        'STARTOFFSET' => -1
                    ],
                    'LASTSUN' => [
                        'BYMONTH' => 5,
                        'BYDAY' => '-1SU',
                        'STARTOFFSET' => -1
                    ],
                    'SPECIAL' => [
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
                    'NTHSUN' => [
                        'BYMONTH' => 5,
                        'BYDAY' => '4SU',
                        'OFFSET' => '-1FR',
                        'STARTOFFSET' => -1
                    ],
                    'NTHDAY' => [
                        'BYMONTH' => 5,
                        'BYDAY' => '4FR',
                        'STARTOFFSET' => -1
                    ],
                    'LASTSUN' => [
                        'BYMONTH' => 5,
                        'BYDAY' => '-1SU',
                        'OFFSET' => '-1FR',
                        'STARTOFFSET' => -1
                    ],
                    'SPECIAL' => [
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

            //    [[
            //     'NTHSUN' => ['BYMONTH' => 6,
            //                    'BYDAY' => '1SU',
            //                   'OFFSET' => '-1FR',
            //              'STARTOFFSET' => -2],
            //    'LASTDAY' => ['BYMONTH' => 5,
            //                    'BYDAY' => '-1FR',
            //              'STARTOFFSET' => -2],
            //   ], 
            //   ['start' => '2019-05-29T10:30:00+00:00',
            //      'end' => '2019-05-31T16:00:00+00:00']],

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
        $this->assertEquals(['christmas' => \DateTime::createFromFormat('!Y-m-d', '2019-12-25', new \DateTimeZone('UTC'))], $this->rule->ymd_to_datetime(['christmas' => '2019-12-25']));
    }
}
