<?php

namespace ByTIC\DataObjects\Tests\Behaviors\Castable;

use ByTIC\DataObjects\Tests\AbstractTest;
use ByTIC\DataObjects\Tests\Fixtures\Models\Books\Book;
use Nip\Utility\Date;

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
        $book->published = $date;

        $dateGet = $book->published;
        self::assertInstanceOf(\DateTime::class, $dateGet);
        self::assertSame($date, $dateGet->format('Y-m-d'));
    }

    public function test_transformInboundValue_Datetime()
    {
        $book = new Book();
        $date = date('Y-m-d');
        $book->published = $date;

        $dateGet = $book->published;
        self::assertInstanceOf(\DateTime::class, $dateGet);
        self::assertSame($date, $dateGet->format('Y-m-d'));
        self::assertSame($date . ' 00:00:00', $book->getPropertyRaw('published'));

        $book = new Book();
        $date = Date::now();
        $book->published = $date;

        $dateGet = $book->published;
        $dateFormatted = $dateGet->format('Y-m-d H:i:s');
        self::assertInstanceOf(\DateTime::class, $dateGet);
        self::assertSame($dateFormatted, $dateGet->format('Y-m-d H:i:s'));
        self::assertSame($dateFormatted, $book->getPropertyRaw('published'));
    }
}
