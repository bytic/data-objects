<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Tests\Behaviors\Timestampable;

use ByTIC\DataObjects\Tests\AbstractTest;
use ByTIC\DataObjects\Tests\Fixtures\Models\Books\Books;

class TimestampableManagerTraitTest extends AbstractTest
{
    public function test_hookTimestampableIntoLifecycle()
    {
        $object = new Books();
        $object->bootTimestampableManagerTrait();

        $events = Books::$events;
        self::assertArrayHasKey('creating', $events);
        self::assertCount(1, $events['creating']);

        self::assertArrayNotHasKey('updating', $events);
    }
}
