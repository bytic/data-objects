<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Behaviors\ArrayAccess;

/**
 * Trait ArrayAccessTrait.
 */
trait ArrayAccessTrait
{
    /**
     * Determine if the given attribute exists.
     *
     * @param mixed $offset
     */
    public function offsetExists($offset): bool
    {
        return isset($this->{$offset}) && null !== $this->{$offset};
    }

    /**
     * Get the value for a given offset.
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
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->{$offset} = $value;
    }

    /**
     * Unset the value for a given offset.
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->{$offset});
    }
}
