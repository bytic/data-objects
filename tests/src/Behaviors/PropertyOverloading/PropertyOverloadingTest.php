<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Tests\Behaviors\PropertyOverloading;

use ByTIC\DataObjects\BaseDto;
use ByTIC\DataObjects\Tests\AbstractTest;

/**
 * Class PropertyOverloadingTest.
 */
class PropertyOverloadingTest extends AbstractTest
{
    public function testFill()
    {
        $object = new BaseDto();
        $object->fill(['test1' => 'value1']);
        self::assertSame('value1', $object->test1);

        $object->fill(['test1' => 'value11', 'test2' => 'value2']);
        self::assertSame('value11', $object->test1);
        self::assertSame('value2', $object->test2);
    }

    public function testSetIf()
    {
        $object = new BaseDto();
        $object->exist = 456;

        $object->setIf('dnx1', 123, true);
        $object->setIf('dnx2', 123, false);
        $object->setIf('exist', 123, false);

        self::assertSame(123, $object->get('dnx1'));
        self::assertNull($object->get('dnx2'));
        self::assertSame(456, $object->get('exist'));
    }

    public function testSetIfNull()
    {
        $object = new BaseDto();
        $object->exist = 456;
        self::assertFalse($object->has('dnx'));
        self::assertNull($object->get('dnx'));

        $object->setIfNull('dnx', 123);
        $object->setIfNull('dnx', 123);

        self::assertSame(123, $object->get('dnx'));
        self::assertSame(456, $object->get('exist'));
    }

    public function testSetIfEmpty()
    {
        $object = new BaseDto();
        $object->exist = 456;
        $object->dnx1 = '';
        $object->dnx2 = '0';
        $object->dnx3 = null;

        $object->setIfEmpty('dnx1', 123);
        $object->setIfEmpty('dnx2', 123);
        $object->setIfEmpty('dnx3', 123);
        $object->setIfEmpty('exist', 123);

        self::assertSame(123, $object->get('dnx1'));
        self::assertSame(123, $object->get('dnx2'));
        self::assertSame(123, $object->get('dnx3'));
        self::assertSame(456, $object->get('exist'));
    }

    public function testIncrementProperty()
    {
        $object = new BaseDto(['field' => '']);
        $object->incrementProperty('field', 1);
        self::assertEquals(1, $object->getPropertyRaw('field'));

        $object = new BaseDto(['field' => '4']);
        $object->incrementProperty('field', 1);
        self::assertEquals(5, $object->getPropertyRaw('field'));
    }
}
