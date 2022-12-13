<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Casts;

/**
 * Interface CastsInboundAttributes.
 */
interface CastsInboundAttributes
{
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
