<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Tests\Behaviors\WithAttributes;

use ByTIC\DataObjects\BaseDto;
use ByTIC\DataObjects\Tests\AbstractTest;
use ByTIC\DataObjects\Tests\Fixtures\Dto\ObjectHasAttributes;
use ByTIC\DataObjects\Tests\Fixtures\Models\Books\Book;

use function PHPUnit\Framework\assertSame;

/**
 * Class HasAttributesTraitTest.
 */
class HasAttributesTraitTest extends AbstractTest
{
    public function testGetAttributesEmptyArray()
    {
        $object = new BaseDto();
        self::assertIsArray($object->getAttributes());
    }

    public function testSetAttribute()
    {
        $object = new BaseDto();
        self::assertFalse($object->hasAttribute('test'));

        $object->setAttribute('test', 'value');
        self::assertTrue($object->hasAttribute('test'));
        self::assertSame('value', $object->getAttribute('test'));
    }

    public function testSetAttributeWithInternalProperty()
    {
        $object = new Book();
        self::assertFalse($object->hasAttribute('name'));

        $object->name = 'test';
        self::assertSame('Test', $object->getAttribute('name'));
        self::assertSame('Test', $object->name);
        self::assertSame('Test', $object->getName());
    }

    public function testGetAttributeNull()
    {
        $object = new ObjectHasAttributes();
        $object->setAttribute('test', null);

        assertSame(['test' => null], $object->getAttributes());
        self::assertNull($object->getAttribute('test'));

        self::assertNull($object->getAttribute('test', false));
    }
}
