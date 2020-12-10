<?php

namespace ByTIC\DataObjects\Tests\Fixtures\Dto;

use ByTIC\DataObjects\BaseDto;
use ByTIC\DataObjects\Behaviors\Serializable\SerializableTrait;

/**
 * Class Serializable
 * @package ByTIC\DataObjects\Tests\Fixtures\Dto
 */
class Serializable extends BaseDto
{
    use SerializableTrait;

    protected $serializable = ['attributes'];
}
