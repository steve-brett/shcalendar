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
      ], ['2019-05-05']],

      [['BYMONTH' => 5,
        'BYDAY' => '2SU',
        'OFFSET' => 0
      ], ['2019-05-12']],

      [['BYMONTH' => 5,
        'BYDAY' => '3SU',
        'OFFSET' => 0
      ], ['2019-05-19']],

      [['BYMONTH' => 6,
        'BYDAY' => '1SU',
        'OFFSET' => 0
      ], ['2019-06-02']],

      [['BYMONTH' => 7,
        'BYDAY' => '1SU',
        'OFFSET' => 0
      ], ['2019-07-07']],


    ];
  }

  /**
   * @dataProvider happyPathSundayDataProvider
   */
  public function testHappyPathSunday(array $expectedValue, array $inputValue): void
  {
    $this->assertEquals($expectedValue, $this->rule->create(\DateTime::createFromFormat('!Y-m-d', $inputValue[0])) );
  }


  public function happyPathSundayOffsetDataProvider(): array
  {
    return [
      [['BYMONTH' => 5,
        'BYDAY' => '1SU',
        'OFFSET' => -1
      ], '2019-05-04'],

      [['BYMONTH' => 5,
        'BYDAY' => '2SU',
        'OFFSET' => -1
      ], '2019-05-11'],

      [['BYMONTH' => 7,
        'BYDAY' => '1SU',
        'OFFSET' => -1
      ], '2018-06-30'],

      [['BYMONTH' => 1,
        'BYDAY' => '1SU',
        'OFFSET' => -1
      ], '2016-12-31'],

      [['BYMONTH' => 5,
        'BYDAY' => '1SU',
        'OFFSET' => -2
      ], '2019-05-03'],

      [['BYMONTH' => 5,
        'BYDAY' => '1SU',
        'OFFSET' => -6
      ], '2019-04-29'],

    ];
  }

  /**
   * @dataProvider happyPathSundayOffsetDataProvider
   */
  public function testHappyPathSundayOffset(array $expectedValue, string $inputValue): void
  {
    $this->assertEquals($expectedValue, $this->rule->create(\DateTime::createFromFormat('!Y-m-d', $inputValue)));
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
