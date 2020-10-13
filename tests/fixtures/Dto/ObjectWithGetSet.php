<?php

namespace ByTIC\DataObjects\Tests\Fixtures\Dto;

use ByTIC\DataObjects\BaseDto;

/**
 * Class ObjectWithGetSet
 * @package ByTIC\DataObjects\Tests\Fixtures\Dto
 *
 * @property string $name
 */
class ObjectWithGetSet extends BaseDto
{
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function setName($value)
    {
        return $this->name = $value;
    }
}
