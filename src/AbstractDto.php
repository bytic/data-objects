<?php

declare(strict_types=1);

namespace ByTIC\DataObjects;

use ByTIC\DataObjects\Utility\Constants;

/**
 * Class AbstractDto.
 */
abstract class AbstractDto implements \ArrayAccess
{
    use Behaviors\Accessors\AccessorsTrait;
    use Behaviors\ArrayAccess\ArrayAccessTrait;
    use Behaviors\PropertyOverloading\PropertyOverloadingTrait;

    /**
     * @param null $default
     *
     * @return mixed|void
     */
    public function get($name, $default = null)
    {
        $return = $this->callAccessors('get', $name);
        if (\is_string($return) && Constants::NO_ACCESSORS_FOUND == $return) {
            return $this->transformValue($name, $this->getPropertyValue($name, $default));
        }

        return $return;
    }

    /**
     * @return mixed
     */
    public function set($name, $value)
    {
        $return = $this->callAccessors('set', $name, [$value]);
        if (\is_string($return) && Constants::NO_ACCESSORS_FOUND == $return) {
            return $this->setPropertyValue($name, $value);
        }

        return $return;
    }
}
