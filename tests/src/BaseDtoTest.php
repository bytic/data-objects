<?php

namespace ByTIC\DataObjects\Tests;

use ByTIC\DataObjects\Tests\Fixtures\Models\Books\Book;

/**
 * Class BaseDtoTest
 * @package ByTIC\DataObjects\Tests
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
