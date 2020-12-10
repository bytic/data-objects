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
    protected $title;

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

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }
}
