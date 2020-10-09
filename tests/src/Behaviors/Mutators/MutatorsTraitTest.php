<?php

namespace ByTIC\DataTransferObject\Tests\Behaviors\Mutators;

use ByTIC\DataTransferObject\Tests\AbstractTest;
use ByTIC\DataTransferObject\Tests\Fixtures\Models\Books\Book;

/**
 * Class ArrayAccessTraitTest
 * @package ByTIC\DataTransferObject\Tests\Behaviors\ArrayAccess
 */
class MutatorsTraitTest extends AbstractTest
{

    public function test_compileMutators_only_once()
    {
        $book1 = new Book();
        $book1->name = 'Test';

        self::assertSame('Test', $book1->name);
        self::assertSame(1, $book1::$compiled);
    }

}
