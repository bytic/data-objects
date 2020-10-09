<?php

namespace ByTIC\DataTransferObject\Behaviors\Mutators;

use ByTIC\DataTransferObject\Behaviors\Accessors\AccessorsTrait;
use ByTIC\DataTransferObject\Utility\Constants;
use Nip\Inflector\Inflector;
use Nip\Utility\Str;

/**
 * Trait MutatorsTrait
 * @package ByTIC\DataTransferObject\Behaviors\Mutators
 */
trait MutatorsTrait
{

    /**
     * The attributes that should use mutators.
     *
     * @var array
     */
    protected static $mutators = [
        'get' => [],
        'set' => [],
    ];

    /**
     * @param $key
     * @return mixed
     */
    public function getMutated($key)
    {
        return $this->callMutator('get', $key);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function setMutated($key, $value)
    {
        return $this->callMutator('set', $key, [$value]);
    }

    /**
     * @param string $type
     * @param string $key
     * @param array $params
     * @return mixed
     */
    protected function callMutator(string $type, string $key, $params = [])
    {
        $method = static::getMutator($type, $key);
        if ($method) {
            return $this->{$method}(...$params);
        }
        return Constants::NO_MUTATOR_FOUND;
    }

    /**
     * Determine if a set mutator exists for an attribute.
     *
     * @param string $key
     * @return bool
     */
    protected function hasSetMutator(string $key): bool
    {
        return $this->hasMutator('set', $key);
    }


    /**
     * Determine if a get mutator exists for an attribute.
     *
     * @param string $key
     * @return bool
     */
    protected function hasGetMutator(string $key): bool
    {
        return $this->hasMutator('get', $key);
    }

    /**
     * Determine if a set mutator exists for an attribute.
     *
     * @param string $type
     * @param string $key
     * @return bool
     */
    protected static function hasMutator(string $type, string $key): bool
    {
        return !empty(static::getMutator($type, $key));
    }

    protected static function getMutator(string $type, string $key): string
    {
        $class = static::class;

        if (isset(static::$mutators[$class][$type][$key])) {
            return static::$mutators[$class][$type][$key];
        }

        if (empty(static::$mutators[$class])) {
            static::compileMutators();
        }

        if (!empty(static::$mutators[$class])) {
            return static::$mutators[$class][$type][$key] = '';
        }

        if (!isset(static::$mutators[$class][$type][$key])) {
            static::$mutators[$class][$type][$key] = '';
        }

        return static::$mutators[$class][$type][$key];
    }

    protected static function compileMutators()
    {
        $class = static::class;

        foreach (get_class_methods($class) as $method) {
            $prefix = substr($method, 1, 3);
            if ($prefix !== 'get' && $prefix !== 'set') {
                continue;
            }

            $field = substr($method, 4);
            if (Str::endsWith($field, 'Attribute')) {
                $field = substr($method, 0, -9);
            }

            static::compileMutatorsMethod($class, $prefix, $method, $field);
        }
    }

    /**
     * @param $class
     * @param $prefix
     * @param $method
     * @param $field
     */
    protected static function compileMutatorsMethod($class, $prefix, $method, $field)
    {
        $field = lcfirst($field);
        static::$mutators[$class][$prefix][$field] = $method;

        /** @noinspection PhpDynamicAsStaticMethodCallInspection */
        $snakeField = Inflector::underscore($field);
        static::$mutators[$class][$prefix][$snakeField] = $method;

        $titleField = ucfirst($field);
        static::$mutators[$class][$prefix][$titleField] = $method;
    }
}
