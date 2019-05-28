<?php

declare(strict_types=1);

namespace SHCalendar\Test;

use SHCalendar\Rule;
use PHPUnit\Framework\TestCase;

class RuleTest extends TestCase  # Has to be [ClassName]Test
{
  /**
   * @var NthSundayRule
   */
  private $rule;

  protected function setUp()
  {
    $this->rule = new Rule();
  }

  public function happyPathDataProvider(): array
  {
    return [
      ['The first Sunday in May',
      ['BYMONTH' => 5,
        'BYDAY' => '1SU',
        'OFFSET' => 0
      ]],


    ];
  }

  /**
   * @dataProvider happyPathDataProvider
   */
  public function testHappyPath(string $expectedValue, array $inputValue): void
  {
    $this->assertEquals($expectedValue, $this->rule->readable($inputValue) );
  }

  //
  // public function invalidData(): array
  // {
  //   return [
  //     ['1799-12-31'],
  //     ['2019-06-30'],
  //     ['2019-06-24'],
  //   ];
  // }
  //
  // /**
  //  * @dataProvider invalidData
  //  */
  // public function testThrowsException(string $inputValue): void
  // {
  //   $this->expectException(\InvalidArgumentException::class);
  //   $this->rule->create(\DateTime::createFromFormat('Y-m-d', $inputValue));
  // }
  //
}
 ?>