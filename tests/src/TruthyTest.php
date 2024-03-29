<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Tests;

use ByTIC\DataObjects\Truthy;

class TruthyTest extends AbstractTest
{
    public function testTruthy()
    {
        $true = new Truthy('<true>1</true>'); // XML with content eval's to TRUE
        static::assertTrue((bool) $true);
        if (false == $true) {
            static::assertFalse(true);
        }

        $false = new Truthy('<false></false>'); // empty XML eval's to FALSE
        static::assertFalse((bool) $false);
        if (true == $false) {
            static::assertFalse(true);
        }
    }
}
