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
      [['BYMONTH' => '5',
        'BYDAY' => '2SU',
        'OFFSET' => '-1'
        ], '2019-05-11'],
    ];
  }

  /**
   * @dataProvider happyPathDataProvider
   */
  public function testHappyPath(array $expectedValue, string $inputValue): void
  {
    $this->assertEquals($expectedValue, $this->rule->create(\DateTime::createFromFormat('Y-m-d', $inputValue)));
  }

}

 ?>
