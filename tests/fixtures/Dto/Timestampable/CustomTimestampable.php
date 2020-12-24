<?php

namespace ByTIC\DataObjects\Tests\Fixtures\Dto\Timestampable;

use ByTIC\DataObjects\BaseDto;
use ByTIC\DataObjects\Behaviors\Timestampable\TimestampableTrait;

/**
 * Class CustomTimestampable
 * @package ByTIC\DataObjects\Tests\Fixtures\Dto\Timestampable
 */
class CustomTimestampable extends BaseDto
{
    use TimestampableTrait;

    /**
     * @var string
     */
    static protected $createTimestamps = ['created'];

    /**
     * @var string
     */
    static protected $updateTimestamps = ['modified'];
}
