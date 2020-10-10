<?php

namespace ByTIC\DataObjects;

use ByTIC\DataObjects\Utility\Constants;

/**
 * Class BaseDto
 * @package ByTIC\DataObjects
 */
class BaseDto extends AbstractDto
{
    use Behaviors\TrackOriginal\TrackOriginalTrait;
    use Behaviors\WithAttributes\HasAttributesTrait;
    use Behaviors\Castable\CastableTrait;

    /**
     * Allows filling in Entity parameters during construction.
     *
     * @param array|null $data
     */
    public function __construct(array $data = null)
    {
        $this->syncOriginal();
        $this->fill($data);
    }

}
