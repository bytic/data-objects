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

}
