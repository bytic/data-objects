<?php

namespace ByTIC\DataTransferObject\Behaviors\ArrayAccess;

/**
 * Trait ArrayAccessTrait
 * @package ByTIC\DataTransferObject\Behaviors\ArrayAccess
 */
trait ArrayAccessTrait
{

    /**
     * Determine if the given attribute exists.
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->{$offset}) && !is_null($this->{$offset});
    }

    /**
     * Get the value for a given offset.
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            return null;
        }
        return $this->{$offset};
    }

    /**
     * Set the value for a given offset.
     *
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
    }

    /**
     * Unset the value for a given offset.
     *
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->{$offset});
    }
}
