<?php

namespace ByTIC\DataObjects\Tests\Behaviors\ArrayAccess;

use ByTIC\DataObjects\BaseDto;
use ByTIC\DataObjects\Tests\AbstractTest;

/**
 * Class ArrayAccessTraitTest
 * @package ByTIC\DataObjects\Tests\Behaviors\ArrayAccess
 */
class ArrayAccessTraitTest extends AbstractTest
{
    public function test_offsetExists()
    {
        $object = new BaseDto();
        $object->test = 'true';

        self::assertTrue($object->offsetExists('test'));
        self::assertFalse($object->offsetExists('testDnx'));
    }

    public function test_offsetGet()
    {
        $object = new BaseDto();
        $object->test = 'true';

        self::assertSame('true', $object->offsetGet('test'));
        self::assertSame('true', $object['test']);
        self::assertNull($object->offsetGet('testDnx'));
    }

    public function test_offsetSet()
    {
        $object = new BaseDto();

        $object->offsetSet('test', 'true');
        self::assertSame('true', $object->offsetGet('test'));

        $object->offsetSet('test', 'true2');
        self::assertSame('true2', $object->offsetGet('test'));
    }

    public function test_offsetUnset()
    {
        $object = new BaseDto();

        $object['test'] = 'true';
        self::assertTrue($object->offsetExists('test'));

        $object->offsetUnset('test');
        self::assertFalse($object->offsetExists('test'));

        $object['test'] = 'true';
        self::assertTrue($object->offsetExists('test'));

        unset($object['test']);
        self::assertFalse($object->offsetExists('test'));
    }
}
