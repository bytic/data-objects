<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Tests\Behaviors\ArrayAccess;

use ByTIC\DataObjects\BaseDto;
use ByTIC\DataObjects\Tests\AbstractTest;

/**
 * Class ArrayAccessTraitTest.
 */
class ArrayAccessTraitTest extends AbstractTest
{
    public function testOffsetExists()
    {
        $object = new BaseDto();
        $object->test = 'true';

        self::assertTrue($object->offsetExists('test'));
        self::assertFalse($object->offsetExists('testDnx'));
    }

    public function testOffsetGet()
    {
        $object = new BaseDto();
        $object->test = 'true';

        self::assertSame('true', $object->offsetGet('test'));
        self::assertSame('true', $object['test']);
        self::assertNull($object->offsetGet('testDnx'));
    }

    public function testOffsetSet()
    {
        $object = new BaseDto();

        $object->offsetSet('test', 'true');
        self::assertSame('true', $object->offsetGet('test'));

        $object->offsetSet('test', 'true2');
        self::assertSame('true2', $object->offsetGet('test'));
    }

    public function testOffsetUnset()
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
