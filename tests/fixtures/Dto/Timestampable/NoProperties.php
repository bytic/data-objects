<?php

namespace ByTIC\DataObjects\Tests\Fixtures\Dto\Timestampable;

use ByTIC\DataObjects\BaseDto;
use ByTIC\DataObjects\Behaviors\Timestampable\TimestampableTrait;

/**
 * Class NoProperties
 * @package ByTIC\DataObjects\Tests\Fixtures\Dto
 */
class NoProperties extends BaseDto
{
    use TimestampableTrait;

}
