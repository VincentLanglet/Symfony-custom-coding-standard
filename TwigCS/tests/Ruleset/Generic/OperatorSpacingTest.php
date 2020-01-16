<?php

declare(strict_types=1);

namespace TwigCS\Tests\Ruleset\Generic;

use TwigCS\Ruleset\Generic\OperatorSpacingSniff;
use TwigCS\Tests\AbstractSniffTest;

/**
 * Class OperatorSpacingTest
 */
class OperatorSpacingTest extends AbstractSniffTest
{
    public function testSniff(): void
    {
        $this->checkGenericSniff(new OperatorSpacingSniff(), [
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
            [21 => 5],
            [21 => 5],
            [22 => 5],
            [22 => 5],
            [35 => 10],
            [35 => 10],
        ]);
    }
}
