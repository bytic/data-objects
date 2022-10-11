<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Tests;

use ByTIC\DataObjects\Truthy;

/**
 *
 */
class TruthyTest extends AbstractTest
{
    public function test_truthy()
    {
        $true = new Truthy('<true>1</true>'); // XML with content eval's to TRUE
        static::assertTrue((bool)$true);
        if ($true == false) {
            static::assertFalse(true);
        }

        $false = new Truthy('<false></false>'); // empty XML eval's to FALSE
        static::assertFalse((boolean)$false);
        if ($false == true) {
            static::assertFalse(true);
        }

    }
}
