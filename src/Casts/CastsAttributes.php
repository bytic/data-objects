<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Casts;

/**
 * Interface CastsAttributes.
 */
interface CastsAttributes
{
    /**
     * Transform the attribute from the underlying model values.
     *
     * @param object $model
     * @param mixed  $value
     *
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes);

    /**
     * Transform the attribute to its underlying model values.
     *
     * @param object $model
     * @param mixed  $value
     *
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes);
}
