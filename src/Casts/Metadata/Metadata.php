<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Casts\Metadata;

use ArrayObject as BaseArrayObject;
use ByTIC\DataObjects\Behaviors\Serializable\SerializableTrait;

/**
 * Class ArrayObject.
 */
class Metadata extends BaseArrayObject implements \JsonSerializable, \Serializable
{
    use SerializableTrait;

    /**
     * @var callable|null
     */
    protected $observer = null;

    /**
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        if (false === strpos($key, '.')) {
            $value = $this->getByKey($key);
        } else {
            $value = $this->getByPath($key);
        }

        return null === $value ? $default : $value;
    }

    /**
     * @return $this
     */
    public function set($name, $value)
    {
        return $this->setDataItem($name, $value);
    }

    /**
     * @param string $key
     *
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
     *
     * @return bool
     */
    public function hasByKey($key)
    {
        return isset($this[$key]);
    }

    /**
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
     * @return $this
     */
    protected function setDataItem($name, $value)
    {
        if (null === $name) {
            $this[] = $value;

            return $this;
        }

        if (false === strpos($name, '.')) {
            $this[$name] = $value;
            $this->callObserver();

            return $this;
        }
        $parts = explode('.', $name);
        $ptr = &$this;

        if (\is_array($parts)) {
            foreach ($parts as $part) {
                if ('[]' == $part) {
                    if (\is_array($ptr)) {
                        $ptr = &$ptr[];
                    }
                } else {
                    if (!isset($ptr[$part])) {
                        $ptr[$part] = [];
                    }

                    $ptr = &$ptr[$part];
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
     * {@inheritDoc}
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

    /**
     * {@inheritDoc}
     */
    public function offsetSet(mixed $key, mixed $value): void
    {
        parent::offsetSet($key, $value);
        $this->callObserver();
    }

    public function setObserver(?callable $observer): self
    {
        $this->observer = $observer;

        return $this;
    }

    protected function callObserver()
    {
        \call_user_func($this->observer, $this);
    }

    public function merge(Metadata|array $metadata2): void
    {
        $metadata2 = $metadata2 instanceof Metadata ? $metadata2->toArray() : (array)$metadata2;
        $metadata2 = array_filter($metadata2);
        $this->exchangeArray(
            array_merge($this->getArrayCopy(), $metadata2)
        );
        $this->callObserver();
    }
}
