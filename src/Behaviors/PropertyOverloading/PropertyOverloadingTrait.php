<?php

namespace ByTIC\DataObjects\Behaviors\PropertyOverloading;

/**
 * Trait PropertyOverloadingTrait.
 */
trait PropertyOverloadingTrait
{
    use \ByTIC\DataObjects\Legacy\Behaviors\OldAccessingPatternsTrait;

    /**
     * @return $this
     *
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function fill(array $data = null)
    {
        if (!\is_array($data)) {
            return $this;
        }

        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @param null $default
     *
     * @return mixed|void
     */
    public function get($name, $default = null)
    {
        return $this->getPropertyValue($name, $default);
    }

    /**
     * @param null $default
     *
     * @return mixed
     */
    protected function getPropertyValue($name, $default = null)
    {
        return $this->transformValue($name, $this->getPropertyRaw($name, $default));
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     *
     * @noinspection PhpUnusedParameterInspection
     */
    protected function transformValue($key, $value)
    {
        return $value;
    }

    /**
     * @param null $default
     *
     * @return mixed|null
     */
    protected function getPropertyRaw(string $name, $default = null)
    {
        return $this->{$name} ?? $default;
    }

    /**
     * @return mixed
     */
    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }

    /**
     * @return mixed
     */
    public function set($name, $value)
    {
        return $this->setPropertyValue($name, $value);
    }

    /**
     * @return mixed|void
     */
    public function setIf($name, $value, $condition)
    {
        $condition = \is_callable($condition) ? $condition() : $condition;
        if (true !== $condition) {
            return;
        }

        return $this->setPropertyValue($name, $value);
    }

    /**
     * @return mixed|void
     */
    public function setIfNull($name, $value)
    {
        return $this->setIf(
            $name,
            $value,
            function () use ($name) {
                return !$this->has($name) || null == $this->get($name);
            }
        );
    }

    /**
     * @return mixed|void
     */
    public function setIfEmpty($name, $value)
    {
        return $this->setIf(
            $name,
            $value,
            function () use ($name) {
                return !$this->has($name) || empty($this->get($name));
            }
        );
    }

    /**
     * @return mixed
     */
    protected function setPropertyValue($name, $value)
    {
        $value = $this->transformInboundValue($name, $value);

        return $this->{$name} = $value;
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     *
     * @noinspection PhpUnusedParameterInspection
     */
    protected function transformInboundValue($key, $value)
    {
        return $value;
    }

    public function __isset($key): bool
    {
        return $this->has($key);
    }

    /**
     * @param string|array $key
     */
    public function has($key): bool
    {
        foreach ((array) $key as $prop) {
            if (null === $this->get($prop)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return $this
     */
    public function __unset($key)
    {
        return $this->unset($key);
    }

    /**
     * @return $this
     */
    public function unset($field)
    {
        $field = (array) $field;
        foreach ($field as $prop) {
            $this->unsetProperty($prop);
        }

        return $this;
    }

    protected function unsetProperty($prop)
    {
        unset($this->{$prop});
    }

    /**
     * Checks that a field is empty.
     *
     * This is not working like the PHP `empty()` function. The method will
     * return true for:
     *
     * - `''` (empty string)
     * - `null`
     * - `[]`
     *
     * and false in all other cases.
     *
     * @param string $field the field to check
     */
    public function isEmpty(string $field): bool
    {
        $value = $this->get($field);

        if (null === $value) {
            return true;
        }

        if (\is_array($value) && empty($value)) {
            return true;
        }

        if (\is_string($value) && empty($value)) {
            return true;
        }

        return false;
    }

    /**
     * Checks tha a field has a value.
     *
     * This method will return true for
     *
     * - Non-empty strings
     * - Non-empty arrays
     * - Any object
     * - Integer, even `0`
     * - Float, even 0.0
     *
     * and false in all other cases.
     *
     * @param string $field the field to check
     */
    public function hasValue(string $field): bool
    {
        return !$this->isEmpty($field);
    }

    public function incrementProperty($name, $value)
    {
        $value = $this->getPropertyFloatWithCheck($name) + $value;
        $this->setPropertyValue($name, $value);
    }

    public function decrementProperty($name, $value)
    {
        $value = $this->getPropertyFloatWithCheck($name) - $value;
        $this->setPropertyValue($name, $value);
    }

    /**
     * @return float|int
     *
     * @throws \Exception
     */
    protected function getPropertyFloatWithCheck($name)
    {
        $value = $this->getPropertyRaw($name);
        if (\in_array($value, [null, '', '0'])) {
            return 0;
        }
        $float = (float) $value;
        if ($float == $value) {
            return $float;
        }
        throw new \Exception('Invalid parameter value is not float');
    }
}
