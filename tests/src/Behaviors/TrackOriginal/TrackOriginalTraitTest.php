<?php

namespace ByTIC\DataObjects\Tests\Behaviors\TrackOriginal;

use ByTIC\DataObjects\Tests\AbstractTest;
use ByTIC\DataObjects\Tests\Fixtures\Models\Books\Book;

/**
 * Class TrackOriginalTraitTest
 * @package ByTIC\DataObjects\Tests\Behaviors\TrackOriginal
 */
class TrackOriginalTraitTest extends AbstractTest
{
    public function test_getDirty_with_empty_array()
    {
        $book = new Book();
        $book->name = 'name1';
        $book->title = 'title1';

        self::assertSame(['name' => 'Name1', 'title' => 'title1'], $book->getDirty());
    }
    public function test_getDirty_with_fields()
    {
        $book = new Book();
        $book->name = 'name1';
        $book->title = 'title1';

        self::assertSame(['name' => 'Name1'], $book->getDirty(['name']));
    }
}
