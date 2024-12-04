<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Tests\Fixtures\Models\Books;

use ByTIC\DataObjects\BaseDto;
use ByTIC\DataObjects\Behaviors\Timestampable\TimestampableManagerTrait;
use ByTIC\DataObjects\Behaviors\Timestampable\TimestampableTrait;
use ByTIC\DataObjects\Casts\AsArrayObject;
use ByTIC\DataObjects\Casts\Metadata\AsMetadataObject;

/**
 * Class Book.
 */
class Books
{
    use TimestampableManagerTrait;

    public $timestamps = true;

    public static $events = [];

    public static function creating($callback)
    {
        self::$events['creating'][] = $callback;
    }
}
