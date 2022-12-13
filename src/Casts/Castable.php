<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Casts;

/**
 * Interface Castable.
 */
interface Castable
{
    /**
     * Get the name of the caster class to use when casting from / to this cast target.
     *
     * @return string
     * @return string|\Illuminate\Contracts\Database\Eloquent\CastsAttributes|\Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes
     */
    public static function castUsing(array $arguments);
}
