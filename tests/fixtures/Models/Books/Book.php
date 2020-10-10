<?php

namespace ByTIC\DataObjects\Tests\Fixtures\Models\Books;

use ByTIC\DataObjects\BaseDto;

/**
 * Class Book
 * @package ByTIC\DataObjects\Tests\Fixtures\Models\Books
 */
class Book extends BaseDto
{
    protected $name;
    protected $title;

    protected $casts = [
        'created' => 'datetime'
    ];

    public static $compiled = 0;

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
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
