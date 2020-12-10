<?php

namespace ByTIC\DataObjects\Tests\Fixtures\Dto;

use ByTIC\DataObjects\BaseDto;
use ByTIC\DataObjects\Behaviors\Timestampable\TimestampableTrait;

/**
 * Class Timestampable
 * @package ByTIC\DataObjects\Tests\Fixtures\Dto
 */
class TimestampableNoProperties extends BaseDto
{
    use TimestampableTrait;

}
