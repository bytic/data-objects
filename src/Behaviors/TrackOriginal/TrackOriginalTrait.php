<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Behaviors\TrackOriginal;

use Nip\Utility\Arr;

/**
 * Trait TrackOriginalTrait.
 */
trait TrackOriginalTrait
{
    use \ByTIC\DataObjects\Legacy\Behaviors\OldDBDataTrait;

    /**
     * The Object original state.
     *
     * @var array
     */
    protected $original = [];

    public function fillOriginal($data)
    {
        foreach ($data as $key => $value) {
            $this->original[$key] = $value;
        }
    }

    public function getOriginalData(): array
    {
        return $this->original;
    }

    /**
     * Returns the value of an original field by name.
     *
     * @param string $field   the name of the field for which original value is retrieved
     * @param null   $default
     *
     * @return mixed
     */
    public function getOriginal(string $field, $default = null)
    {
        if (!\strlen($field)) {
            throw new \InvalidArgumentException('Cannot get an empty field');
        }

        if (\array_key_exists($field, $this->original)) {
            return $this->original[$field];
        }

        return $this->get($field, $default);
    }

    /**
     * Get the model's raw original attribute values.
     *
     * @param string|null $key
     * @param mixed       $default
     *
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
    public function syncOriginal(): self
    {
        $keys = array_merge(array_keys($this->original), array_keys($this->getAttributes()));
        $keys = array_unique(array_filter($keys));
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = $this->getPropertyRaw($key);
        }
        $this->original = $data;

        return $this;
    }

    /**
     * Determine if the model or any of the given attribute(s) have been modified.
     *
     * @param array|string|null $attributes
     *
     * @return bool
     */
    public function isDirty($attributes = null)
    {
        return $this->hasChanges(
            $this->getDirty(),
            \is_array($attributes) ? $attributes : \func_get_args()
        );
    }

    /**
     * Determine if the model and all the given attribute(s) have remained the same.
     *
     * @param array|string|null $attributes
     *
     * @return bool
     */
    public function isClean($attributes = null)
    {
        return !$this->isDirty(...\func_get_args());
    }

    /**
     * Determine if the model or any of the given attribute(s) have been modified.
     *
     * @param array|string|null $attributes
     *
     * @return bool
     */
    public function wasChanged($attributes = null)
    {
        return $this->hasChanges(
            $this->getChanges(),
            \is_array($attributes) ? $attributes : \func_get_args()
        );
    }

    /**
     * Get the attributes that have been changed since last sync.
     *
     * @param null $fields
     */
    public function getDirty($fields = null): array
    {
        $dirty = [];
        $fields = \is_array($fields) && \count($fields) > 0 ? $fields : array_keys($this->getAttributes());
        foreach ($fields as $field) {
            $value = $this->getPropertyRaw($field);
            if (!$this->originalIsEquivalent($field, $value)) {
                $dirty[$field] = $value;
            }
        }

        return $dirty;
    }

    /**
     * Determine if any of the given attributes were changed.
     *
     * @param array             $changes
     * @param array|string|null $attributes
     *
     * @return bool
     */
    protected function hasChanges($changes, $attributes = null)
    {
        // If no specific attributes were provided, we will just see if the dirty array
        // already contains any attributes. If it does we will just return that this
        // count is greater than zero. Else, we need to check specific attributes.
        if (empty($attributes)) {
            return \count($changes) > 0;
        }

        // Here we will spin through every attribute and see if this is in the array of
        // dirty attributes. If it is, we will return true and if we make it through
        // all of the attributes for the entire array we will return false at end.
        foreach (Arr::wrap($attributes) as $attribute) {
            if (\array_key_exists($attribute, $changes)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param null $value
     */
    protected function originalIsEquivalent(string $key, $value = null): bool
    {
        if (!\array_key_exists($key, $this->original)) {
            return false;
        }
        $attribute = $value ?? $this->getPropertyRaw($key);
        $original = Arr::get($this->original, $key);

        if ($attribute === $original) {
            return true;
        }

        return is_numeric($attribute) && is_numeric($original)
            && 0 === strcmp((string) $attribute, (string) $original);
    }
}
