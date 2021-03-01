<?php

namespace ByTIC\DataObjects\Casts;

/**
 * Interface CastsInboundAttributes
 * @package ByTIC\DataObjects\Casts
 */
interface CastsInboundAttributes
{
    /**
     * Transform the attribute to its underlying model values.
     *
     * @param object $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes);
}
