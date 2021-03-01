<?php

namespace ByTIC\DataObjects\Casts;

use ArrayObject as BaseArrayObject;
use JsonSerializable;
use Serializable;

/**
 * Class ArrayObject
 * @package ByTIC\DataObjects\Casts
 */
class ArrayObject extends BaseArrayObject implements JsonSerializable, Serializable
{
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
    public function serialize()
    {
        return serialize($this->toArray());
    }

    /**
     * Constructs the object.
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized The string representation of the object.
     * @return void
     */
    public function unserialize($data)
    {
        $data = @unserialize($data);
        if (!is_array($data)) {
            return;
        }
        foreach ($data as $property => $value) {
            $this[$property] = $value;
        }
    }

    /**
     * Get the array that should be JSON serialized.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->getArrayCopy();
    }
}
