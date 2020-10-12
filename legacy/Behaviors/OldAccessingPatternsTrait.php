<?php

namespace ByTIC\DataObjects\Legacy\Behaviors;

use ByTIC\DataObjects\Behaviors\PropertyOverloading\PropertyOverloadingTrait;

/**
 * Trait OldAccessingPatternsTrait
 * @package ByTIC\DataObjects\Legacy\Behaviors
 */
trait OldAccessingPatternsTrait
{
    /**
     * @param bool $data
     * @return \ByTIC\DataObjects\BaseDto|PropertyOverloadingTrait
     * @deprecated use fill($data)
     */
    public function writeData($data = false)
    {
        return $this->fill($data);
    }

    /**
     * @deprecated use
     * @param $key
     * @param $value
     * @return mixed
     */
    protected function setDataValue($key, $value)
    {
        return $this->setPropertyValue($key, $value);
    }

    /**
     * Get an attribute from the $attributes array.
     *
     * @param string $key
     * @return mixed
     */
    protected function getAttributeFromArray(string $key)
    {
        return $this->getPropertyRaw($key);
    }

}
