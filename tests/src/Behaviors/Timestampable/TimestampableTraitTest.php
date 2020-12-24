<?php

namespace ByTIC\DataObjects\Tests\Behaviors\Timestampable;

use ByTIC\DataObjects\Tests\AbstractTest;
use ByTIC\DataObjects\Tests\Fixtures\Dto\Timestampable\CreateTimestamps;
use ByTIC\DataObjects\Tests\Fixtures\Dto\Timestampable\CustomTimestampable;
use ByTIC\DataObjects\Tests\Fixtures\Dto\Timestampable\NoProperties;
use ByTIC\DataObjects\Tests\Fixtures\Models\Books\Book;
use Mockery\Mock;
use Nip\Utility\Date;

/**
 * Class TimestampableTraitTest
 * @package ByTIC\DataObjects\Tests\Behaviors\Timestampable
 */
class TimestampableTraitTest extends AbstractTest
{
    public function test_getTimestampsAttributes()
    {
        $object = new CustomTimestampable();

        self::assertSame(['created'], $object->getCreateTimestamps());
        self::assertSame(['modified'], $object->getUpdateTimestamps());
        self::assertSame([], $object->getTimestampAttributes(''));
        self::assertSame([], $object->getTimestampAttributes('test'));
        self::assertSame(['created', 'modified'], $object->getTimestampAttributes('create'));
        self::assertSame(['modified'], $object->getTimestampAttributes('update'));
    }

    public function test_setModelTimeAttribute()
    {
        $object = new CustomTimestampable();

        $now = Date::now();
        $object->setModelTimeAttribute('created', null);
        $created = $object->created;
        self::assertInstanceOf(\DateTime::class, $created);
        self::assertSame($now->toDateTimeString('minute'), $created->toDateTimeString('minute'));

        $object->setModelTimeAttribute('created', '');
        $created = $object->created;
        self::assertInstanceOf(\DateTime::class, $created);
        self::assertSame($now->toDateTimeString('minute'), $created->toDateTimeString('minute'));

        $object->setModelTimeAttribute('created', ' ');
        $created = $object->created;
        self::assertInstanceOf(\DateTime::class, $created);
        self::assertSame($now->toDateTimeString('minute'), $created->toDateTimeString('minute'));

        $now = $now->add('hour', 3);
        $object->setModelTimeAttribute('created', $now->timestamp);
        $created = $object->created;
        self::assertInstanceOf(\DateTime::class, $created);
        self::assertSame($now->toDateTimeString('minute'), $created->toDateTimeString('minute'));

        $now = $now->add('hour', 3);
        $object->setModelTimeAttribute('created', $now->toDateTimeString());
        $created = $object->created;
        self::assertInstanceOf(\DateTime::class, $created);
        self::assertSame($now->toDateTimeString('minute'), $created->toDateTimeString('minute'));

        self::expectException(\Exception::class);
        $object->setModelTimeAttribute('created', 'test');
    }

    public function test_hookCastableTrait()
    {
        $book = new Book();
        $book->bootTimestampableTrait();

        $date1 = Date::now();
        $book->created_at = $date1->toDateTimeString();

        $dateGet = $book->created_at;
        self::assertInstanceOf(\DateTime::class, $dateGet);
        self::assertSame($date1->toDateTimeString(), $dateGet->toDateTimeString());
    }

    public function test_usesTimestamps_called_once()
    {
        /** @var Mock|NoProperties $book */
        $book = \Mockery::mock(NoProperties::class)->shouldAllowMockingProtectedMethods()->makePartial();
        $book->shouldReceive('usesTimestampsDefault')->once()->andReturn(true);

        $book->usesTimestamps();
        $book->usesTimestamps();
        $book->usesTimestamps();
        self::assertTrue($book->usesTimestamps());
    }

    public function test_updatedTimestamps()
    {
        $book = new Book();
        $date1 = Date::now();
        $book->updatedTimestamps('create');

        $dateGet = $book->created_at;
        self::assertInstanceOf(\DateTime::class, $dateGet);
        self::assertSame($date1->toDateTimeString(), $dateGet->toDateTimeString());

        $dateOld = $date1->sub('days', 5);
        $date1 = Date::now();
        $book->created_at = $dateOld;
        $book->updatedTimestamps('update');

        $dateGet = $book->created_at;
        self::assertInstanceOf(\DateTime::class, $dateGet);
        self::assertSame($dateOld->toDateTimeString(), $dateGet->toDateTimeString());

        $dateGet = $book->updated_at;
        self::assertInstanceOf(\DateTime::class, $dateGet);
        self::assertSame($date1->toDateTimeString(), $dateGet->toDateTimeString());
    }

    public function test_createTimestamps()
    {
        $object = new CreateTimestamps();

        self::assertSame(['created'], $object->getCreateTimestamps());
        self::assertSame([], $object->getUpdateTimestamps());
        self::assertSame([], $object->getTimestampAttributes(''));
        self::assertSame([], $object->getTimestampAttributes('test'));
        self::assertSame(['created'], $object->getTimestampAttributes('create'));
        self::assertSame([], $object->getTimestampAttributes('update'));

    }
}
