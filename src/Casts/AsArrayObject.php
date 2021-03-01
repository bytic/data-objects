<?php

namespace ByTIC\DataObjects\Casts;

/**
 * Class AsArrayObject
 * @package ByTIC\DataObjects\Casts
 */
class AsArrayObject implements Castable
{
    /**
     * Get the caster class to use when casting from / to this cast target.
     *
     * @param array $arguments
     * @return object|string
     */
    public static function castUsing(array $arguments)
    {
        $encoder = isset($arguments[0]) ? $arguments[0] : null;
        return new class($encoder) implements CastsAttributes {

            protected $encoder = 'json';

            /**
             *  constructor.
             * @param $encoder
             */
            public function __construct($encoder)
            {
                $this->encoder = $encoder;
            }

            /**
             * @inheritDoc
             */
            public function get($model, $key, $value, $attributes): ArrayObject
            {
                if (empty($value)) {
                    $value = [];
                } else {
                    $value = $this->decode($value);
                }
                if ($value === null) {
                    $value = [];
                }
                return new ArrayObject($value);
            }

            /**
             * @inheritDoc
             */
            public function set($model, $key, $value, $attributes)
            {
                if (is_string($value)) {
                    return [$key => $value];
                }
                if ($value instanceof ArrayObject) {
                    $value = $this->encode($value);
                }
                return [$key => $value];
            }

            /**
             * @inheritDoc
             */
            public function serialize($model, string $key, $value, array $attributes)
            {
                return $value->getArrayCopy();
            }

            /**
             * @param $value
             * @return false|string
             */
            protected function encode($value)
            {
                if ($this->encoder == 'serialize') {
                    return $value instanceof ArrayObject ? $value->serialize() : serialize($value);
                }
                return json_encode($value);
            }

            /**
             * @param $value
             * @return mixed
             */
            protected function decode($value)
            {
                if ($this->encoder == 'serialize') {
                    return unserialize($value);
                }
                return json_decode($value, true);
            }
        };
    }
}
