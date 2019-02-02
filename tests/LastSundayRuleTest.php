<?php

declare(strict_types=1);

namespace SHCalendar\Test;

use SHCalendar\LastSundayRule;
use PHPUnit\Framework\TestCase;

class LastSundayRuleTest extends TestCase  # Has to be [ClassName]Test
{
  /**
   * @var LastSundayRule
   */
  private $rule;

  protected function setUp()
  {
    $this->rule = new LastSundayRule();
  }

  public function happyPathSundayDataProvider(): array
  {
    return [
      [false, '2019-05-05'],

    ];
  }

  /**
   * @dataProvider happyPathSundayDataProvider
   */
  public function testHappyPathSunday(array $expectedValue, string $inputValue): void
  {
    $this->assertEquals($expectedValue, $this->rule->create(\DateTime::createFromFormat('!Y-m-d', $inputValue)));
  }

  public function happyPathSundayOffsetDataProvider(): array
  {
    return [


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
