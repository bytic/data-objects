<?php

namespace ByTIC\DataObjects\Behaviors\WithAttributes;

/**
 * Trait HasAttributesTrait
 * @package ByTIC\DataObjects\Behaviors\WithAttributes
 */
trait HasAttributesTrait
{
    protected $attributes = [];

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasAttribute($key): bool
    {
        foreach ((array)$key as $prop) {
            if ($this->getAttribute($prop) === null) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function getPropertyRaw(string $key, $default = null)
    {
        if (!isset($this->attributes[$key])) {
            return $default;
        }
        return $this->attributes[$key];
    }

    /**
     * Get an attribute from the model.
     *
     * @param string $key
     * @return mixed|void
     */
    public function getAttribute(string $key, $default = null)
    {
        if (!isset($this->attributes[$key])) {
            return $default;
        }
        return $this->attributes[$key];
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setPropertyValue($key, $value)
    {
        if (property_exists($this, $key)) {
            $this->{$key} = $value;
        }
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * @param $prop
     */
    protected function unsetProperty($prop)
    {
        if (property_exists($this, $prop)) {
            unset($this->{$prop});
        }
        $this->unsetAttribute($prop);
    }

    /**
     * @param $key
     */
    public function unsetAttribute($key)
    {
        if ($this->hasAttribute($key)) {
            unset($this->attributes[$key]);
        }
    }
}
