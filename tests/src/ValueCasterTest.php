<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Tests;

use ByTIC\DataObjects\ValueCaster;
use Carbon\Carbon;

/**
 * Class ValueCasterTest.
 */
class ValueCasterTest extends AbstractTest
{
    /**
     * @dataProvider data_asDateTime
     */
    public function testAsDateTime($input, $output)
    {
        self::assertEquals($output, ValueCaster::asDate($input));
    }

    public function data_asDateTime(): array
    {
        return [
            ['', null],
            ['0000-00-00', null],
            ['2020-01-01', Carbon::create(2020, 01, 01)],
        ];
    }
}
