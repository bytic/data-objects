<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Tests\Behaviors\Castable;

use ByTIC\DataObjects\Tests\AbstractTest;
use ByTIC\DataObjects\Tests\Fixtures\Models\Books\Book;
use ByTIC\DataObjects\ValueCaster;
use Nip\Utility\Date;

/**
 * Class CastableTraitTest.
 */
class CastableTraitTest extends AbstractTest
{
    public function testCastsDatetime()
    {
        $book = new Book();
        $date = date('Y-m-d');
        $book->published = $date;

        $dateGet = $book->published;
        self::assertInstanceOf(\DateTime::class, $dateGet);
        self::assertSame($date, $dateGet->format('Y-m-d'));
    }

    public function testTransformInboundValueDatetime()
    {
        $book = new Book();

        $book->published = null;
        self::assertNull($book->published);

        $book->published = '0000-00-00 00:00:00';
        self::assertNull($book->published);

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

    public function testCastsValueCache()
    {
        $book = \Mockery::mock(Book::class)->makePartial();
        $book->shouldAllowMockingProtectedMethods();
        $book->shouldNotReceive('castValue')->twice()->andReturnUsing(
            function ($key, $value) {
                return ValueCaster::as($value, 'datetime');
            }
        );

        $date = date('Y-m-d');
        $book->published = $date;

        $book->get('published');
        $book->get('published');
        $dateGet = $book->published;
        self::assertInstanceOf(\DateTime::class, $dateGet);
        self::assertSame($date, $dateGet->format('Y-m-d'));
        self::assertSame($date . ' 00:00:00', $book->getAttribute('published'));

        $book->published = '2019-01-01';

        $book->get('published');
        $book->get('published');
        $dateGet = $book->published;
        self::assertInstanceOf(\DateTime::class, $dateGet);
        self::assertSame('2019-01-01', $dateGet->format('Y-m-d'));
        self::assertSame('2019-01-01 00:00:00', $book->getAttribute('published'));
    }
}
