<?php

namespace ByTIC\DataObjects\Tests\Behaviors\PropertyOverloading;

use ByTIC\DataObjects\BaseDto;
use ByTIC\DataObjects\Tests\AbstractTest;

/**
 * Class PropertyOverloadingTest
 * @package ByTIC\DataObjects\Tests\Behaviors\PropertyOverloading
 */
class PropertyOverloadingTest extends AbstractTest
{
    public function test_fill()
    {
        $object = new BaseDto();
        $object->fill(['test1' => 'value1']);
        self::assertSame('value1', $object->test1);

        $object->fill(['test1' => 'value11','test2' => 'value2']);
        self::assertSame('value11', $object->test1);
        self::assertSame('value2', $object->test2);
    }
}
