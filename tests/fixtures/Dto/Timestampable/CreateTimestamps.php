<?php

namespace ByTIC\DataObjects\Tests\Fixtures\Dto\Timestampable;

use ByTIC\DataObjects\BaseDto;
use ByTIC\DataObjects\Behaviors\Timestampable\TimestampableTrait;

/**
 * Class CreateTimestamps
 * @package ByTIC\DataObjects\Tests\Fixtures\Dto\Timestampable
 */
class CreateTimestamps extends BaseDto
{
    use TimestampableTrait;

    /**
     * @var string
     */
    static protected $createTimestamps = ['created'];
}
