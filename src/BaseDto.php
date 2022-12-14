<?php

declare(strict_types=1);

namespace ByTIC\DataObjects;

/**
 * Class BaseDto.
 */
class BaseDto extends AbstractDto
{
    use Behaviors\Castable\CastableTrait;
    use Behaviors\TrackOriginal\TrackOriginalTrait;
    use Behaviors\WithAttributes\HasAttributesTrait;

    /**
     * Allows filling in Entity parameters during construction.
     */
    public function __construct(array $data = null)
    {
        $this->syncOriginal();
        $this->fill($data);
    }
}
