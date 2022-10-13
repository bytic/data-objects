<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Behaviors\ArrayAccess;

/**
 * Trait ArrayAccessTrait
 * @package ByTIC\DataObjects\Behaviors\ArrayAccess
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
    public function offsetGet(mixed $offset): mixed
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
    public function offsetSet(mixed $offset,mixed $value): void
    {
        $this->{$offset} = $value;
    }

    /**
     * Unset the value for a given offset.
     *
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->{$offset});
    }
}
