<?php

namespace ByTIC\DataObjects\Tests\Behaviors\Accessors;

use ByTIC\DataObjects\Tests\AbstractTest;
use ByTIC\DataObjects\Tests\Fixtures\Dto\ObjectWithGetSet;
use ByTIC\DataObjects\Tests\Fixtures\Models\Books\Book;

/**
 * Class AccessorsTraitTest
 * @package ByTIC\DataObjects\Tests\Behaviors\ArrayAccess
 */
class AccessorsTraitTest extends AbstractTest
{

    public function test_compileMutators_only_once()
    {
        $book1 = new Book();
        $book1->name = 'Test';

        self::assertSame('Test', $book1->name);
        self::assertSame('Test', $book1->get('name'));
        self::assertSame(1, $book1::$compiled);
    }

    public function test_getter_internal_property()
    {
        $book1 = new Book(['name' => 'test']);

        self::assertSame('Test', $book1->name);
        self::assertSame('Test', $book1->getName());
        self::assertSame('Test', $book1->getAttribute('name'));
    }

    public function test_getter_magic_property()
    {
        $object = new ObjectWithGetSet(['name' => 'test']);

        self::assertSame('test', $object->name);
        self::assertSame('test', $object->getName());
        self::assertSame('test', $object->get('name'));
    }

    public function test_callAccessors_setter()
    {
        $book1 = new Book();
        $book1->name = 'test';

        self::assertSame('Test', $book1->name);
    }

    public function test_setter_magic_property()
    {
        $object = new ObjectWithGetSet(['name' => 'test']);

        self::assertSame('test', $object->name);

        $object->name = 'test1';
        self::assertSame('test1', $object->name);
        self::assertSame('test1', $object->getName());
        self::assertSame('test1', $object->get('name'));
    }
}
