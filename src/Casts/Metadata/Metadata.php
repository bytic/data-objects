<?php

namespace ByTIC\DataObjects\Casts\Metadata;

use ArrayObject as BaseArrayObject;
use JsonSerializable;
use Serializable;

/**
 * Class ArrayObject
 * @package ByTIC\DataObjects\Casts\Metadata
 */
class Metadata extends BaseArrayObject implements JsonSerializable, Serializable
{
    /**
     * @var null|callable
     */
    protected $observer = null;

    /**
     * @param string $key
     * @param $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        if (strpos($key, '.') === false) {
            $value = $this->getByKey($key);
        } else {
            $value = $this->getByPath($key);
        }

        return $value === null ? $default : $value;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function set($name, $value)
    {
        return $this->setDataItem($name, $value);
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getByKey($key)
    {
        if ($this->hasByKey($key)) {
            return $this[$key];
        }

        return null;
    }

    /**
     * Determine if the given configuration value exists.
     *
     * @param string $key
     * @return bool
     */
    public function hasByKey($key)
    {
        return isset($this[$key]);
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getByPath(string $path)
    {
        $segments = explode('.', $path);
        $value = $this;
        foreach ($segments as $segment) {
            if (isset($value[$segment])) {
                $value = $value[$segment];
            } else {
                return null;
            }
        }

        return $value;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    protected function setDataItem($name, $value)
    {
        if (null === $name) {
            $this[] = $value;
            return $this;
        }

        if (strpos($name, '.') === false) {
            $this[$name] = $value;
            return $this;
        }
        $parts = explode('.', $name);
        $ptr =& $this;

        if (is_array($parts)) {
            foreach ($parts as $part) {
                if ('[]' == $part) {
                    if (is_array($ptr)) {
                        $ptr =& $ptr[];
                    }
                } else {
                    if (!isset($ptr[$part])) {
                        $ptr[$part] = [];
                    }

                    $ptr =& $ptr[$part];
                }
            }
        }

        $ptr = $value;
        $this->callObserver();

        return $this;
    }

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

    /**
     * @inheritDoc
     */
    public function offsetSet($key, $value)
    {
        parent::offsetSet($key, $value);
        $this->callObserver();
    }

    /**
     * @param callable|null $observer
     * @return Metadata
     */
    public function setObserver(?callable $observer): self
    {
        $this->observer = $observer;
        return $this;
    }

    protected function callObserver()
    {
        call_user_func($this->observer, $this);
    }

}
