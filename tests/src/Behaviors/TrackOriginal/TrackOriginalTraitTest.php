<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Tests\Behaviors\TrackOriginal;

use ByTIC\DataObjects\Tests\AbstractTest;
use ByTIC\DataObjects\Tests\Fixtures\Models\Books\Book;

/**
 * Class TrackOriginalTraitTest.
 */
class TrackOriginalTraitTest extends AbstractTest
{
    public function testGetDirtyWithEmptyArray()
    {
        $book = new Book();
        $book->name = 'name1';
        $book->title = 'title1';

        self::assertSame(['name' => 'Name1', 'title' => 'title1'], $book->getDirty());
    }

    public function testGetDirtyWithFields()
    {
        $book = new Book();
        $book->name = 'name1';
        $book->title = 'title1';

        self::assertSame(['name' => 'Name1'], $book->getDirty(['name']));
    }

    public function testSyncOriginal()
    {
        $book = new Book();
        self::assertSame([], $book->getOriginalData());

        $book->fill(['name' => 'name1', 'title' => 'title1']);
        self::assertSame([], $book->getOriginalData());

        $book->fillOriginal(['name' => 'name1', 'title' => 'title1']);
        self::assertSame(['name' => 'name1', 'title' => 'title1'], $book->getOriginalData());

        $book->fill(['name' => 'name2', 'title' => 'title2']);
        $book->syncOriginal();
        self::assertSame(['name' => 'Name2', 'title' => 'title2'], $book->getOriginalData());
    }
}
