<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Casts;

/**
 * Class AsArrayObject.
 */
class AsArrayObject implements Castable
{
    /**
     * Get the caster class to use when casting from / to this cast target.
     *
     * @return object|string
     */
    public static function castUsing(array $arguments)
    {
        $encoder = $arguments[0] ?? null;

        return new class($encoder) implements CastsAttributes {
            protected $encoder = 'json';

            /**
             *  constructor.
             */
            public function __construct($encoder)
            {
                $this->encoder = $encoder;
            }

            /**
             * {@inheritDoc}
             */
            public function get($model, $key, $value, $attributes): ArrayObject
            {
                if (empty($value)) {
                    $value = [];
                } else {
                    $value = $this->decode($value);
                }
                if (!\is_array($value)) {
                    $value = [];
                }

                return new ArrayObject($value);
            }

            /**
             * {@inheritDoc}
             */
            public function set($model, $key, $value, $attributes)
            {
                if (\is_string($value)) {
                    return [$key => $value];
                }
                if ($value instanceof ArrayObject) {
                    $value = $this->encode($value);
                }

                return [$key => $value];
            }

            /**
             * {@inheritDoc}
             */
            public function serialize($model, string $key, $value, array $attributes)
            {
                return $value->getArrayCopy();
            }

            /**
             * @return false|string
             */
            protected function encode($value)
            {
                if ('serialize' == $this->encoder) {
                    return $value instanceof ArrayObject ? $value->serialize() : serialize($value);
                }

                return json_encode($value);
            }

            /**
             * @return mixed
             */
            protected function decode($value)
            {
                if ('serialize' == $this->encoder) {
                    return unserialize($value);
                }

                return json_decode($value, true);
            }
        };
    }
}
