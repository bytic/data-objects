<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Tests\Behaviors\Accessors;

use ByTIC\DataObjects\Tests\AbstractTest;
use ByTIC\DataObjects\Tests\Fixtures\Dto\ObjectWithGetSet;
use ByTIC\DataObjects\Tests\Fixtures\Models\Books\Book;
use ByTIC\DataObjects\Utility\Constants;

/**
 * Class AccessorsTraitTest.
 */
class AccessorsTraitTest extends AbstractTest
{
    public function testCompileMutatorsOnlyOnce()
    {
        $book1 = new Book();
        $book1->name = 'Test';

        self::assertSame('Test', $book1->name);
        self::assertSame('Test', $book1->get('name'));
        self::assertSame(1, $book1::$compiled);
    }

    public function testGetterInternalProperty()
    {
        $book1 = new Book(['name' => 'test']);

        self::assertSame('Test', $book1->name);
        self::assertSame('Test', $book1->getName());
        self::assertSame('Test', $book1->getAttribute('name'));
    }

    public function testGetterMagicProperty()
    {
        $object = new ObjectWithGetSet(['name' => 'test', 'options' => 'test']);

        self::assertSame('test', $object->name);
        self::assertSame('test', $object->getName());
        self::assertSame('test', $object->get('name'));

        self::assertSame('test', $object->options);
        self::assertSame('test', $object->getOptions());
        self::assertSame('test', $object->get('options'));
    }

    public function testSetterPrivateProperty()
    {
        $object = new ObjectWithGetSet(['name' => 'test', 'value_id' => '1']);

        self::assertSame('test', $object->getName());
        self::assertSame(1, $object->getValueId());
    }

    public function testCallAccessorsSetter()
    {
        $book1 = new Book();
        $book1->name = 'test';

        self::assertSame('Test', $book1->name);
    }

    public function testCompileMutators()
    {
        $book1 = new Book();
        self::assertSame(Constants::NO_ACCESSORS_FOUND, $book1->getMutated('attribute'));
    }

    public function testSetterMagicProperty()
    {
        $object = new ObjectWithGetSet();
        self::assertNull($object->name);

        $object = new ObjectWithGetSet(['name' => 'test']);

        self::assertSame('test', $object->name);

        $object->name = 'test1';
        self::assertSame('test1', $object->name);
        self::assertSame('test1', $object->getName());
        self::assertSame('test1', $object->get('name'));
    }
}
