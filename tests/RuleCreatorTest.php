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
      [['BYMONTH' => 5,
        'BYDAY' => '1SU',
        'OFFSET' => 0
      ],
      '2019-05-05'],

      [['BYMONTH' => 5,
        'BYDAY' => '2SU',
        'OFFSET' => 0
      ],
      '2019-05-12'],

      [['BYMONTH' => 5,
        'BYDAY' => '3SU',
        'OFFSET' => 0
      ],
      '2019-05-19'],

      [['BYMONTH' => 6,
        'BYDAY' => '1SU',
        'OFFSET' => 0
      ],
      '2019-06-02'],

      [['BYMONTH' => 7,
        'BYDAY' => '1SU',
        'OFFSET' => 0
      ],
      '2019-07-07'],


    ];
  }

  /**
   * @dataProvider happyPathSundayDataProvider
   */
  public function testHappyPathSunday(array $expectedValue, string $inputValue): void
  {
    $this->assertEquals($expectedValue, $this->rule->nthDay(\DateTime::createFromFormat('!Y-m-d', $inputValue) ) );
  }


  public function happyPathSundayOffsetDataProvider(): array
  {
    return [
      [['BYMONTH' => 5,
        'BYDAY' => '1SU',
        'OFFSET' => -1
      ],
      [ 'date' => '2019-05-04',
        'refday' => 'Sun'
      ]],

      [['BYMONTH' => 5,
        'BYDAY' => '2SU',
        'OFFSET' => -1
      ],
      [ 'date' => '2019-05-11',
        'refday' => 'Sun'
      ]],

      [['BYMONTH' => 7,
        'BYDAY' => '1SU',
        'OFFSET' => -1
      ],
      [ 'date' => '2018-06-30',
        'refday' => 'Sun'
      ]],

      [['BYMONTH' => 1,
        'BYDAY' => '1SU',
        'OFFSET' => -1
      ],
      [ 'date' => '2016-12-31',
        'refday' => 'Sun'
      ]],

      [['BYMONTH' => 5,
        'BYDAY' => '1SU',
        'OFFSET' => -2
      ],
      [ 'date' => '2019-05-03',
        'refday' => 'Sun'
      ]],

      [['BYMONTH' => 5,
        'BYDAY' => '1SU',
        'OFFSET' => -6
      ],
      [ 'date' => '2019-04-29',
        'refday' => 'Sun'
      ]],

    ];
  }

  /**
   * @dataProvider happyPathSundayOffsetDataProvider
   */
  public function testHappyPathSundayOffset(array $expectedValue, array $inputValue): void
  {
    $this->assertEquals($expectedValue, $this->rule->nthDay(\DateTime::createFromFormat('!Y-m-d', $inputValue['date']), $inputValue['refday']) );
  }

  public function happyPathDayDataProvider(): array
  {
    return [
      [['BYMONTH' => 5,
        'BYDAY' => '1SA',
        'OFFSET' => 0
      ],
      '2019-05-04'],

      [['BYMONTH' => 5,
        'BYDAY' => '2SA',
        'OFFSET' => 0
      ],
      '2019-05-11'],

      [['BYMONTH' => 5,
        'BYDAY' => '3SA',
        'OFFSET' => 0
      ],
      '2019-05-18'],

      [['BYMONTH' => 5,
        'BYDAY' => '4SA',
        'OFFSET' => 0
      ],
      '2019-05-25'],

      [['BYMONTH' => 6,
        'BYDAY' => '1SA',
        'OFFSET' => 0
      ],
      '2019-06-01'],

      [['BYMONTH' => 6,
        'BYDAY' => '1MO',
        'OFFSET' => 0
      ],
      '2019-06-03'],

    ];
  }

  /**
   * @dataProvider happyPathDayDataProvider
   */
  public function testHappyPathDay(array $expectedValue, string $inputValue): void
  {
    $this->assertEquals($expectedValue, $this->rule->nthDay(\DateTime::createFromFormat('!Y-m-d', $inputValue) ) );
  }

  public function invalidData(): array
  {
    return [
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
      [['date' => '2019-06-29',
        'refday' => 'Sat']],
      [['date' => '2019-06-24',
        'refday' => 'Sun']],
      [['date' => '2019-07-29',
        'refday' => 'Mon']],
      [['date' => '2019-07-01',
        'refday' => 'Nonsense']],
    ];
  }

  /**
   * @dataProvider invalidDataTwo
   */
  public function testTwoThrowsException(array $inputValue): void
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->rule->nthDay(\DateTime::createFromFormat('!Y-m-d', $inputValue['date']), $inputValue['refday'] );
  }

  /**
   * Last day tests -----------------------------------------------
   */

  public function happyPathLastSundayDataProvider(): array
  {
    return [
      [['BYMONTH' => 5,
        'BYDAY' => '-1SU',
        'OFFSET' => 0
      ], '2019-05-26'],

      [['BYMONTH' => 6,
        'BYDAY' => '-1SU',
        'OFFSET' => 0
      ], '2019-06-30'],

      [['BYMONTH' => 6,
      'BYDAY' => '-1SA',
      'OFFSET' => 0
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
      [['BYMONTH' => 5,
        'BYDAY' => '-1SU',
        'OFFSET' => -1
      ], '2019-05-25'],

    ];
  }

  /**
   * @dataProvider happyPathLastSundayOffsetDataProvider
   */
  public function testHappyPathLastSundayOffset(array $expectedValue, string $inputValue): void
  {
    $this->assertEquals($expectedValue, $this->rule->lastDay(\DateTime::createFromFormat('!Y-m-d', $inputValue)));
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
public function testThreeThrowsException(string $inputValue): void
{
  $this->expectException(\InvalidArgumentException::class);
  $this->rule->lastDay(\DateTime::createFromFormat('Y-m-d', $inputValue));
}

}

 ?>
