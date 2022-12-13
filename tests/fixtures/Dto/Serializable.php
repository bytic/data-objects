<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Tests\Fixtures\Dto;

use ByTIC\DataObjects\BaseDto;
use ByTIC\DataObjects\Behaviors\Serializable\SerializableTrait;

/**
 * Class Serializable.
 */
class Serializable extends BaseDto
{
    use SerializableTrait;

    protected $serializable = ['attributes'];
}
