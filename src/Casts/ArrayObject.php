<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Casts;

use ArrayObject as BaseArrayObject;
use ByTIC\DataObjects\Behaviors\Serializable\SerializableTrait;
use JsonSerializable;
use Serializable;

/**
 * Class ArrayObject
 * @package ByTIC\DataObjects\Casts
 */
class ArrayObject extends BaseArrayObject implements JsonSerializable, Serializable
{
    use SerializableTrait;

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getArrayCopy();
    }

    /**
     * @inheritDoc
     */
    public function __sleep()
    {
        return array_keys($this->getArrayCopy());
    }

    /**
     * Get the array that should be JSON serialized.
     *
     * @return array
     */
    public function jsonSerialize(): mixed
    {
        return $this->getArrayCopy();
    }
}
