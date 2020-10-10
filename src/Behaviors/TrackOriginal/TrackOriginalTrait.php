<?php

namespace ByTIC\DataObjects\Behaviors\TrackOriginal;

use InvalidArgumentException;
use Nip\Utility\Arr;

/**
 * Trait TrackOriginalTrait
 * @package ByTIC\DataObjects\Behaviors\TrackOriginal
 */
trait TrackOriginalTrait
{
    /**
     * The Object original state.
     *
     * @var array
     */
    protected $original = [];

    /**
     * Returns the value of an original field by name
     *
     * @param string $field the name of the field for which original value is retrieved.
     * @param null $default
     * @return mixed
     */
    public function getOriginal(string $field, $default = null)
    {
        if (!strlen($field)) {
            throw new InvalidArgumentException('Cannot get an empty field');
        }

        if (array_key_exists($field, $this->original)) {
            return $this->original[$field];
        }

        return $this->get($field, $default);
    }

    /**
     * Get the model's raw original attribute values.
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed|array
     */
    public function getRawOriginal($key = null, $default = null)
    {
        return Arr::get($this->original, $key, $default);
    }

    /**
     * Sync the original attributes with the current.
     *
     * @return $this
     */
    public function syncOriginal()
    {
        $this->original = $this->getAttributes();
        return $this;
    }

    /**
     * Determine if the model or any of the given attribute(s) have been modified.
     *
     * @param array|string|null $attributes
     * @return bool
     */
    public function isDirty($attributes = null)
    {
        return $this->hasChanges(
            $this->getDirty(),
            is_array($attributes) ? $attributes : func_get_args()
        );
    }

    /**
     * Determine if the model and all the given attribute(s) have remained the same.
     *
     * @param array|string|null $attributes
     * @return bool
     */
    public function isClean($attributes = null)
    {
        return !$this->isDirty(...func_get_args());
    }

    /**
     * Determine if the model or any of the given attribute(s) have been modified.
     *
     * @param array|string|null $attributes
     * @return bool
     */
    public function wasChanged($attributes = null)
    {
        return $this->hasChanges(
            $this->getChanges(),
            is_array($attributes) ? $attributes : func_get_args()
        );
    }

    /**
     * Get the attributes that have been changed since last sync.
     *
     * @return array
     */
    public function getDirty()
    {
        $dirty = [];

        foreach ($this->getAttributes() as $key => $value) {
            if (!$this->originalIsEquivalent($key)) {
                $dirty[$key] = $value;
            }
        }

        return $dirty;
    }

    /**
     * Determine if any of the given attributes were changed.
     *
     * @param array $changes
     * @param array|string|null $attributes
     * @return bool
     */
    protected function hasChanges($changes, $attributes = null)
    {
        // If no specific attributes were provided, we will just see if the dirty array
        // already contains any attributes. If it does we will just return that this
        // count is greater than zero. Else, we need to check specific attributes.
        if (empty($attributes)) {
            return count($changes) > 0;
        }

        // Here we will spin through every attribute and see if this is in the array of
        // dirty attributes. If it is, we will return true and if we make it through
        // all of the attributes for the entire array we will return false at end.
        foreach (Arr::wrap($attributes) as $attribute) {
            if (array_key_exists($attribute, $changes)) {
                return true;
            }
        }

        return false;
    }
}
