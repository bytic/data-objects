<?php

namespace ByTIC\DataTransferObject;

use ByTIC\DataTransferObject\Utility\Constants;

/**
 * Class BaseDto
 * @package ByTIC\DataTransferObject
 */
class BaseDto implements \ArrayAccess
{
    use Behaviors\Accessors\AccessorsTrait;
    use Behaviors\ArrayAccess\ArrayAccessTrait;
    use Behaviors\WithAttributes\HasAttributesTrait;
    use Behaviors\Mutators\MutatorsTrait;

    /**
     * @param $name
     * @param null $default
     * @return mixed|void
     */
    public function get($name, $default = null)
    {
        if (!$name) {
            return;
        }

        $value = $this->getMutated($name);
        if ($value != Constants::NO_MUTATOR_FOUND) {
            return $value;
        }

        if ($this->hasAttribute($name)) {
            return $this->getAttribute($name);
        }

        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        return $default;
    }

    /**
     * @param $name
     * @param $value
     * @return mixed|void
     */
    public function set($name, $value)
    {
        if (!$name) {
            return $this;
        }

        $return = $this->setMutated($name, $value);
        if ($return != Constants::NO_MUTATOR_FOUND) {
            return $return;
        }

        if (property_exists($this, $name)) {
            $this->{$name} = $value;
        }

        return $this->setAttribute($name, $value);
    }

    /**
     * @inheritDoc
     */
    public function unset($field)
    {
        $field = (array)$field;
        foreach ($field as $prop) {
            $this->unsetAttribute($prop);
        }

        return $this;
    }
}
