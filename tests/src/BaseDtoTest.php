<?php

namespace ByTIC\DataTransferObject\Tests;

use ByTIC\DataTransferObject\Tests\Fixtures\Models\Books\Book;

/**
 * Class BaseDtoTest
 * @package ByTIC\DataTransferObject\Tests
 */
class BaseDtoTest extends AbstractTest
{
    public function test_properties_internal_and_in_attributes()
    {
        $book1 = new Book();
        $book1->title = 'Test';
        self::assertSame('Test', $book1->title);
        self::assertSame('Test', $book1->getTitle());
        self::assertSame('Test', $book1->getAttribute('title'));
    }
}
