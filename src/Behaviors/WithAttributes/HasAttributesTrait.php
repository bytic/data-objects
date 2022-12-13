<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Behaviors\WithAttributes;

/**
 * Trait HasAttributesTrait.
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

    public function hasAttribute($key): bool
    {
        foreach ((array) $key as $prop) {
            if (null === $this->getAttribute($prop)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param null $default
     *
     * @return mixed
     */
    public function getPropertyRaw(string $key, $default = null)
    {
        if (property_exists($this, $key)) {
            return $this->{$key};
        }

        return $this->getAttribute($key, $default);
    }

    /**
     * Get an attribute from the model.
     *
     * @return mixed|void
     */
    public function getAttribute(string $key, $default = null)
    {
        if (empty($key)) {
            throw new \InvalidArgumentException('Please provide a key argument');
        }
        if (!isset($this->attributes[$key])) {
            return $default;
        }

        return $this->attributes[$key];
    }

    /**
     * @return static
     */
    public function setPropertyValue($key, $value)
    {
        if (method_exists($this, 'transformInboundValue')) {
            $value = $this->transformInboundValue($key, $value);
        }

        if (property_exists($this, $key)) {
            $this->{$key} = $value;
        }
        $this->setAttribute($key, $value);

        return $this;
    }

    /**
     * @return $this
     *
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    protected function unsetProperty($prop)
    {
        if (property_exists($this, $prop)) {
            unset($this->{$prop});
        }
        $this->unsetAttribute($prop);
    }

    public function unsetAttribute($key)
    {
        if ($this->hasAttribute($key)) {
            unset($this->attributes[$key]);
        }
    }
}
