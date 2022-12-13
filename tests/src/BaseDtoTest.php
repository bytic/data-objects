<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Tests;

use ByTIC\DataObjects\Tests\Fixtures\Models\Books\Book;

/**
 * Class BaseDtoTest.
 */
class BaseDtoTest extends AbstractTest
{
    public function testPropertiesInternalAndInAttributes()
    {
        $book1 = new Book();
        $book1->title = 'Test';
        self::assertSame('Test', $book1->title);
        self::assertSame('Test', $book1->getTitle());
        self::assertSame('Test', $book1->getAttribute('title'));
    }
}
