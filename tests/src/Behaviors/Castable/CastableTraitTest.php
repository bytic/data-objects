<?php

namespace ByTIC\DataObjects\Tests\Behaviors\Castable;

use ByTIC\DataObjects\BaseDto;
use ByTIC\DataObjects\Tests\AbstractTest;
use ByTIC\DataObjects\Tests\Fixtures\Models\Books\Book;

/**
 * Class CastableTraitTest
 * @package ByTIC\DataObjects\Tests\Behaviors\Castable
 */
class CastableTraitTest extends AbstractTest
{
    public function test_castsDatetime()
    {
        $book = new Book();
        $date = date('Y-m-d');
        $book->created = $date;

        $dateGet = $book->created;
        self::assertInstanceOf(\DateTime::class, $dateGet);
        self::assertSame($date, $dateGet->format('Y-m-d'));
    }
}
