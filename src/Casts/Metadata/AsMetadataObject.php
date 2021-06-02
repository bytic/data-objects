<?php

namespace ByTIC\DataObjects\Casts\Metadata;

use ByTIC\DataObjects\Casts\Castable;
use ByTIC\DataObjects\Casts\CastsAttributes;
use Nip\Utility\Json;

/**
 * Class AsMetadataObject
 * @package ByTIC\DataObjects\Casts\Metadata
 */
class AsMetadataObject implements Castable
{
    /**
     * Get the caster class to use when casting from / to this cast target.
     *
     * @param array $arguments
     * @return object|string
     */
    public static function castUsing(array $arguments)
    {
        $encoder = $arguments[0] ?? null;
        $metadataClass = $arguments[1] ?? Metadata::class;

        return new class($encoder, $metadataClass) implements CastsAttributes {

            protected $encoder = 'json';
            protected $metadataClass = Metadata::class;

            /**
             *  constructor.
             * @param $encoder
             */
            public function __construct($encoder, $metadataClass)
            {
                $this->encoder = $encoder;
                $this->metadataClass = $metadataClass;
            }

            /**
             * @inheritDoc
             */
            public function get($model, $key, $value, $attributes): Metadata
            {
                if (empty($value)) {
                    $value = [];
                } else {
                    $value = $this->decode($value);
                }
                if (!is_array($value)) {
                    $value = [];
                }
                return (new $this->metadataClass($value))
                    ->setObserver(
                        function (Metadata $metadata) use ($model, $key) {
                            $model->set($key, $metadata);
                        }
                    );
            }

            /**
             * @inheritDoc
             */
            public function set($model, $key, $value, $attributes)
            {
                if (is_string($value)) {
                    return [$key => $value];
                }
                if ($value instanceof Metadata) {
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
                    return $value instanceof Metadata ? $value->serialize() : serialize($value);
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
                return Json::decode($value, true);
            }
        };
    }
}
