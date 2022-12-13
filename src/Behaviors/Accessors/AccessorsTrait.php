<?php
/** @noinspection PhpMissingStrictTypesDeclarationInspection
 * Do not put strict types to allow for type casting on setter and getter
 */

namespace ByTIC\DataObjects\Behaviors\Accessors;

use ByTIC\DataObjects\Utility\Constants;
use Nip\Inflector\Inflector;
use Nip\Utility\Str;

/**
 * Trait AccessorsTrait.
 */
trait AccessorsTrait
{
    /**
     * The attributes that should use mutators.
     */
    protected static array $accessors = [
        'get' => [],
        'set' => [],
    ];

    /**
     * @return mixed
     *
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function getMutated(string $key)
    {
        if (empty($key)) {
            throw new \InvalidArgumentException('Please provide a key argument');
        }
        /* @noinspection PhpUnhandledExceptionInspection */
        return $this->callAccessors('get', $key);
    }

    /**
     * @return mixed
     *
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function setMutated($key, $value)
    {
        /* @noinspection PhpUnhandledExceptionInspection */
        return $this->callAccessors('set', $key, [$value]);
    }

    /**
     * @param array $params
     *
     * @return mixed
     *
     * @throws \Exception
     */
    protected function callAccessors(string $type, string $key, $params = [])
    {
        $method = static::getMutator($type, $key);
        if (!$method) {
            return Constants::NO_ACCESSORS_FOUND;
        }
        if ('get' !== $type) {
            return $this->{$method}(...$params);
        }

        try {
            set_error_handler(
                function ($errno, $errstr, $errfile, $errline) {
                    throw new \Exception($errstr, $errno);
                },
                \E_NOTICE | \E_WARNING
            );
            $return = $this->{$method}(...$params);
            restore_error_handler();

            return $return;
        } catch (\Exception $exception) {
            restore_error_handler();
            $message = $exception->getMessage();
            if (Str::startsWith($message, 'Undefined property:')
                && Str::endsWith($message, '::$' . $key)) {
                return $this->getPropertyRaw($key);
            }
            throw $exception;
        }
    }

    /**
     * Determine if a set mutator exists for an attribute.
     */
    protected function hasSetMutator(string $key): bool
    {
        return static::hasMutator('set', $key);
    }

    /**
     * Determine if a get mutator exists for an attribute.
     */
    protected function hasGetMutator(string $key): bool
    {
        return static::hasMutator('get', $key);
    }

    /**
     * Determine if a set mutator exists for an attribute.
     */
    protected static function hasMutator(string $type, string $key): bool
    {
        return !empty(static::getMutator($type, $key));
    }

    protected static function getMutator(string $type, string $key): string
    {
        $class = static::class;

        if (empty(static::$accessors[$class])) {
            static::compileMutators();
        }

        if (isset(static::$accessors[$class][$type][$key])) {
            return static::$accessors[$class][$type][$key];
        }

        if (!empty(static::$accessors[$class])) {
            return static::$accessors[$class][$type][$key] = '';
        }

        if (!isset(static::$accessors[$class][$type][$key])) {
            static::$accessors[$class][$type][$key] = '';
        }

        return static::$accessors[$class][$type][$key];
    }

    protected static function compileMutators()
    {
        $class = static::class;

        foreach (get_class_methods($class) as $method) {
            if (\in_array($method, ['get', 'set'])) {
                continue;
            }
            $prefix = substr($method, 0, 3);
            if ('get' !== $prefix && 'set' !== $prefix) {
                continue;
            }

            $field = substr($method, 3);
            if (Str::endsWith($field, 'Attribute')) {
                $field = substr($field, 0, -9);
            }

            if (empty($field)) {
                continue;
            }

            static::compileAccessorsMethod($class, $prefix, $method, $field);
        }
    }

    protected static function compileAccessorsMethod($class, $prefix, $method, $field)
    {
        $field = lcfirst($field);
        static::$accessors[$class][$prefix][$field] = $method;

        /** @noinspection PhpDynamicAsStaticMethodCallInspection */
        $snakeField = Inflector::underscore($field);
        static::$accessors[$class][$prefix][$snakeField] = $method;

        $titleField = ucfirst($field);
        static::$accessors[$class][$prefix][$titleField] = $method;
    }
}
