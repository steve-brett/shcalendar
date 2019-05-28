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

  public function happyPathDataProvider(): array
  {
    return [
      ['The first Sunday in May',
      ['BYMONTH' => 5,
        'BYDAY' => '1SU',
        'OFFSET' => 0
      ]],

      ['The second Sunday in May',
      ['BYMONTH' => 5,
        'BYDAY' => '2SU',
        'OFFSET' => 0
      ]],

      ['The third Sunday in May',
      ['BYMONTH' => 5,
        'BYDAY' => '3SU',
        'OFFSET' => 0
      ]],

      ['The fourth Sunday in May',
      ['BYMONTH' => 5,
        'BYDAY' => '4SU',
        'OFFSET' => 0
      ]],

      ['The last Sunday in May',
      ['BYMONTH' => 5,
        'BYDAY' => '-SU',
        'OFFSET' => 0
      ]],

      ['The first Sunday in June',
      ['BYMONTH' => 6,
        'BYDAY' => '1SU',
        'OFFSET' => 0
      ]],

      ['The first Sunday in July',
      ['BYMONTH' => 7,
        'BYDAY' => '1SU',
        'OFFSET' => 0
      ]],

      ['The first Sunday in August',
      ['BYMONTH' => 8,
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
