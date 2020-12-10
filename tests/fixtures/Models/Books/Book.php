<?php

namespace ByTIC\DataObjects\Tests\Fixtures\Models\Books;

use ByTIC\DataObjects\BaseDto;
use ByTIC\DataObjects\Behaviors\Timestampable\TimestampableTrait;

/**
 * Class Book
 * @package ByTIC\DataObjects\Tests\Fixtures\Models\Books
 */
class Book extends BaseDto
{
    use TimestampableTrait;
    public $timestamps = true;

    protected $name;
    protected $title;

    protected $casts = [
        'published' => 'datetime'
    ];

    public static $compiled = 0;

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->setPropertyValue('name', ucfirst($name));
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTitle()
    {
        return $this->title;
    }

    protected static function compileMutators()
    {
        static::$compiled++;
        parent::compileMutators();
    }
}
