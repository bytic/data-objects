<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Tests\Fixtures\Dto\Timestampable;

use ByTIC\DataObjects\BaseDto;
use ByTIC\DataObjects\Behaviors\Timestampable\TimestampableTrait;

/**
 * Class CustomTimestampable.
 */
class InheritTimestampable extends CreateTimestamps
{

    public $timestamps = false;
}
