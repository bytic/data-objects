<?php

namespace ByTIC\DataTransferObject\Behaviors\Accessors;

/**
 * Trait AccessorsTrait
 * @package ByTIC\DataTransferObject\Behaviors\Accessors
 */
trait AccessorsTrait
{
    /**
     * @param bool $data
     * @return \ByTIC\DataTransferObject\BaseDto|AccessorsTrait
     * @deprecated use fill($data)
     */
    public function writeData($data = false)
    {
        return $this->fill($data);
    }

    /**
     * @param array|null $data
     * @return $this
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function fill(array $data = null)
    {
        if (!is_array($data)) {
            return $this;
        }

        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @param $name
     * @param null $default
     * @return mixed|void
     */
    public function get($name, $default = null)
    {
        return isset($this->{$name}) ? $this->{$name} : $default;
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function set($name, $value)
    {
        return $this->{$name} = $value;
    }

    /**
     * @param $key
     * @return bool
     */
    public function __isset($key): bool
    {
        return $this->has($key);
    }

    /**
     * @param string|array $key
     * @return bool
     */
    public function has($key): bool
    {
        foreach ((array)$key as $prop) {
            if ($this->get($prop) === null) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $key
     * @return $this
     */
    public function __unset($key)
    {
        return $this->unset($key);
    }

    /**
     * @param $field
     * @return $this
     */
    public function unset($field)
    {
        $field = (array)$field;
        foreach ($field as $prop) {
            unset($this->{$prop});
        }

        return $this;
    }

    /**
     * Checks that a field is empty
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
     * @param string $field The field to check.
     * @return bool
     */
    public function isEmpty(string $field): bool
    {
        $value = $this->get($field);

        if ($value === null) {
            return true;
        }

        if (is_array($value) && empty($value)) {
            return true;
        }

        if (is_string($value) && empty($value)) {
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
     * @param string $field The field to check.
     * @return bool
     */
    public function hasValue(string $field): bool
    {
        return !$this->isEmpty($field);
    }
}
