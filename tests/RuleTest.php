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
        'BYDAY' => '1SU',
        'OFFSET' => '0'
      ], '2019-05-05'],

      [['BYMONTH' => '5',
        'BYDAY' => '2SU',
        'OFFSET' => '0'
      ], '2019-05-12'],

      [['BYMONTH' => '5',
        'BYDAY' => '3SU',
        'OFFSET' => '0'
      ], '2019-05-19'],

      [[
        ['BYMONTH' => '5',
        'BYDAY' => '4SU',
        'OFFSET' => '0'
        ],
        ['BYMONTH' => '5',
        'BYDAY' => '-SU',
        'OFFSET' => '0'
        ],
      ], '2019-05-26'],

      [['BYMONTH' => '6',
        'BYDAY' => '1SU',
        'OFFSET' => '0'
      ], '2016-06-02'],

      [['BYMONTH' => '7',
        'BYDAY' => '1SU',
        'OFFSET' => '0'
      ], '2016-07-07'],
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
