<?php

declare(strict_types=1);

namespace SHCalendar\Test;

use SHCalendar\NthDayRule;
use PHPUnit\Framework\TestCase;

class NthDayRuleTest extends TestCase  # Has to be [ClassName]Test
{
  /**
   * @var NthDayRule
   */
  private $rule;

  protected function setUp()
  {
    $this->rule = new NthDayRule();
  }

  public function happyPathDayDataProvider(): array
  {
    return [
      [['BYMONTH' => '5',
        'BYDAY' => '1SA',
        'OFFSET' => '0'
      ], '2019-05-04'],

      [['BYMONTH' => '5',
        'BYDAY' => '2SA',
        'OFFSET' => '0'
      ], '2019-05-11'],

      [['BYMONTH' => '5',
        'BYDAY' => '3SA',
        'OFFSET' => '0'
      ], '2019-05-18'],

      [['BYMONTH' => '5',
        'BYDAY' => '4SA',
        'OFFSET' => '0'
      ], '2019-05-25'],

      [['BYMONTH' => '6',
        'BYDAY' => '1SA',
        'OFFSET' => '0'
      ], '2019-06-01'],


    ];
  }

  /**
   * @dataProvider happyPathDayDataProvider
   */
  public function testHappyPathDay(array $expectedValue, string $inputValue): void
  {
    $this->assertEquals($expectedValue, $this->rule->create(\DateTime::createFromFormat('!Y-m-d', $inputValue)));
  }
/*
  public function happyPathDayOffsetDataProvider(): array
  {
    return [
      [['BYMONTH' => '5',
        'BYDAY' => '1SU',
        'OFFSET' => '-1'
      ], '2019-05-04'],


    ];
  }

  /**
   * @dataProvider happyPathDayOffsetDataProvider
   */ /*
  public function testHappyPathDayOffset(array $expectedValue, string $inputValue): void
  {
    $this->assertEquals($expectedValue, $this->rule->create(\DateTime::createFromFormat('!Y-m-d', $inputValue)));
  }
*/
  public function invalidData(): array
{
  return [
    ['1799-12-31'],
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
