<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Tests\Fixtures\Dto;

use ByTIC\DataObjects\BaseDto;

/**
 * Class ObjectWithGetSet.
 *
 * @property string $name
 */
class ObjectWithGetSet extends BaseDto
{
    protected $title;
    protected $name;
    protected ?int $value_id = null;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function setName($value)
    {
        return $this->name = $value;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
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

    public function getValueId(): ?int
    {
        return $this->value_id;
    }

    public function setValueId(?int $pool_id): void
    {
        $this->value_id = $pool_id;
    }
}
