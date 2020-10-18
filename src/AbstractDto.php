<?php

namespace ByTIC\DataObjects;

use ByTIC\DataObjects\Utility\Constants;

/**
 * Class AbstractDto
 * @package ByTIC\DataObjects
 */
abstract class AbstractDto implements \ArrayAccess
{
    use Behaviors\ArrayAccess\ArrayAccessTrait;
    use Behaviors\PropertyOverloading\PropertyOverloadingTrait;
    use Behaviors\Accessors\AccessorsTrait;

    /**
     * @param $name
     * @param null $default
     * @return mixed|void
     */
    public function get($name, $default = null)
    {
        $return = $this->callAccessors('get', $name);
        if (is_string($return) && $return == Constants::NO_ACCESSORS_FOUND) {
            return $this->transformValue($name, $this->getPropertyValue($name, $default));
        }
        return $return;
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function set($name, $value)
    {
        $return = $this->callAccessors('set', $name, [$value]);
        if (is_string($return) && $return == Constants::NO_ACCESSORS_FOUND) {
            return $this->setPropertyValue($name, $value);
        }
        return $return;
    }
}
