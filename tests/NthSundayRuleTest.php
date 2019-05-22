<?php

declare(strict_types=1);

namespace SHCalendar\Test;

use SHCalendar\NthSundayRule;
use PHPUnit\Framework\TestCase;

class NthSundayRuleTest extends TestCase  # Has to be [ClassName]Test
{
  /**
   * @var NthSundayRule
   */
  private $rule;

  protected function setUp()
  {
    $this->rule = new NthSundayRule();
  }

  public function happyPathSundayDataProvider(): array
  {
    return [
      [['BYMONTH' => 5,
        'BYDAY' => '1SU',
        'OFFSET' => 0
      ],
      [ 'date' => '2019-05-05',
        'refday' => 'Sun'
      ]],

      [['BYMONTH' => 5,
        'BYDAY' => '2SU',
        'OFFSET' => 0
      ],
      [ 'date' => '2019-05-12',
        'refday' => 'Sun'
      ]],

      [['BYMONTH' => 5,
        'BYDAY' => '3SU',
        'OFFSET' => 0
      ],
      [ 'date' => '2019-05-19',
        'refday' => 'Sun'
      ]],

      [['BYMONTH' => 6,
        'BYDAY' => '1SU',
        'OFFSET' => 0
      ],
      [ 'date' => '2019-06-02',
        'refday' => 'Sun'
      ]],

      [['BYMONTH' => 7,
        'BYDAY' => '1SU',
        'OFFSET' => 0
      ],
      [ 'date' => '2019-07-07',
        'refday' => 'Sun'
      ]],


    ];
  }

  /**
   * @dataProvider happyPathSundayDataProvider
   */
  public function testHappyPathSunday(array $expectedValue, array $inputValue): void
  {
    $this->assertEquals($expectedValue, $this->rule->create(\DateTime::createFromFormat('!Y-m-d', $inputValue['date']), $inputValue['refday']) );
  }


  public function happyPathSundayOffsetDataProvider(): array
  {
    return [
      [['BYMONTH' => 5,
        'BYDAY' => '1SU',
        'OFFSET' => -1
      ],
      [ 'date' => '2019-05-04',
        'refday' => '' // Defaults to Sun if empty
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
    $this->assertEquals($expectedValue, $this->rule->create(\DateTime::createFromFormat('!Y-m-d', $inputValue['date']), $inputValue['refday']) );
  }

  public function happyPathDayDataProvider(): array
  {
    return [
      [['BYMONTH' => 5,
        'BYDAY' => '1SA',
        'OFFSET' => 0
      ],
      [ 'date' => '2019-05-04',
        'refday' => 'Sat'
      ]],

    ];
  }

  /**
   * @dataProvider happyPathDayDataProvider
   */
  public function testHappyPathDay(array $expectedValue, array $inputValue): void
  {
    $this->assertEquals($expectedValue, $this->rule->create(\DateTime::createFromFormat('!Y-m-d', $inputValue['date']), $inputValue['refday']) );
  }

  public function invalidData(): array
{
  return [
    ['1799-12-31'],
    ['2019-06-30'],
    ['2019-06-24'],
  ];
}

/**
 * @dataProvider invalidData
 */
public function testThrowsException(string $inputValue): void
{
  $this->expectException(\InvalidArgumentException::class);
  $this->rule->create(\DateTime::createFromFormat('Y-m-d', $inputValue));
}

}

 ?>
