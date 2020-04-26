<?php

declare(strict_types=1);

namespace TwigCS\Tests\Ruleset\Generic\OperatorSpacing;

use TwigCS\Ruleset\Generic\OperatorSpacingSniff;
use TwigCS\Tests\Ruleset\AbstractSniffTest;

/**
 * Class OperatorSpacingTest
 */
class OperatorSpacingTest extends AbstractSniffTest
{
    /**
     * @return void
     */
    public function testSniff(): void
    {
        $this->checkSniff(new OperatorSpacingSniff(), [
            [1 => 4],
            [1 => 4],
            [2 => 5],
            [2 => 5],
            [3 => 5],
            [3 => 5],
            [4 => 5],
            [4 => 5],
            [5 => 5],
            [5 => 5],
            [6 => 5],
            [6 => 5],
            [7 => 5],
            [7 => 5],
            [8 => 7],
            [8 => 7],
            [9 => 10],
            [9 => 10],
            [9 => 19],
            [9 => 19],
            [10 => 5],
            [10 => 5],
            [11 => 6],
            [11 => 6],
            [12 => 11],
            [12 => 11],
            [13 => 11],
            [13 => 11],
            [14 => 7],
            [14 => 7],
            [15 => 7],
            [15 => 7],
            [19 => 5],
            [19 => 5],
            [20 => 5],
            [20 => 5],
            [22 => 6],
            [33 => 10],
            [33 => 10],
        ]);
    }
}
