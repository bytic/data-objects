<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Tests\Fixtures\Dto\Timestampable;

use ByTIC\DataObjects\BaseDto;
use ByTIC\DataObjects\Behaviors\Timestampable\TimestampableTrait;

/**
 * Class NoProperties.
 */
class NoProperties extends BaseDto
{
    use TimestampableTrait;
}
