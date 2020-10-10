<?php

namespace ByTIC\DataObjects\Tests\Behaviors\Accessors;

use ByTIC\DataObjects\Tests\AbstractTest;
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
        self::assertSame(1, $book1::$compiled);
    }

    public function test_getter_internal_property()
    {
        $book1 = new Book(['name' => 'test']);

        self::assertSame('Test', $book1->name);
        self::assertSame('Test', $book1->getName());
        self::assertSame('Test', $book1->getAttribute('name'));
    }

    public function test_callAccessors_setter()
    {
        $book1 = new Book();
        $book1->name = 'test';

        self::assertSame('Test', $book1->name);
    }
}
