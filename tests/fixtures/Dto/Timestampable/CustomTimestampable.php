<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Tests\Fixtures\Dto\Timestampable;

use ByTIC\DataObjects\BaseDto;
use ByTIC\DataObjects\Behaviors\Timestampable\TimestampableTrait;

/**
 * Class CustomTimestampable.
 */
class CustomTimestampable extends BaseDto
{
    use TimestampableTrait;

    /**
     * @var string
     */
    protected static $createTimestamps = ['created'];

    /**
     * @var string
     */
    protected static $updateTimestamps = ['modified'];
}
