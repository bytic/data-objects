<?php

namespace ByTIC\DataTransferObject\Tests\Behaviors\Accessors;

use ByTIC\DataTransferObject\BaseDto;
use ByTIC\DataTransferObject\Tests\AbstractTest;

/**
 * Class AccessorsTraitTest
 * @package ByTIC\DataTransferObject\Tests\Behaviors\ArrayAccess
 */
class AccessorsTraitTest extends AbstractTest
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
