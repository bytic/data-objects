<?php

namespace ByTIC\DataObjects\Behaviors\Serializable;

/**
 * Trait Serializable
 * @package ByTIC\DataObjects\Behaviors\Serializable
 *
 * @property array $serializable
 */
trait SerializableTrait
{
    /**
     * @var array List of attribute names which should be stored in serialized form
     *
     *  protected $serializable = [];
     */


    /**
     * The custom Carbon JSON serializer.
     *
     * @var callable|null
     */
    protected static $serializer;

    /**
     * @return string
     */
    public function serialize(): string
    {
        $properties = $this->__sleep();
        $data = [];
        foreach ($properties as $property) {
            $data[$property] = $this->{$property};
        }
        return serialize($data);
    }

    public static function serializeUsing($callback)
    {
        static::$serializer = $callback;
    }

    /**
     * @param $data
     */
    public function unserialize($data)
    {
        $data = @unserialize($data);
        if (!is_array($data)) {
            return;
        }
        foreach ($data as $property => $value) {
            $this->{$property} = $value;
        }
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return $this->serializable;
    }

    public function __wakeup()
    {
        if (get_parent_class() && method_exists(parent::class, '__wakeup')) {
            parent::__wakeup();
        }
    }
}
