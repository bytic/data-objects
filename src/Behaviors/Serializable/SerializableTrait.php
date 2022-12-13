<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Behaviors\Serializable;

/**
 * Trait Serializable.
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

    public function __serialize(): array
    {
        $properties = $this->__sleep();
        $data = [];
        foreach ($properties as $property) {
            if ($this instanceof \ArrayAccess) {
                $data[$property] = $this[$property];
            } else {
                $data[$property] = $this->{$property};
            }
        }

        return $data;
    }

    /**
     * @internal
     */
    public function serialize(): string
    {
        return serialize($this->__serialize());
    }

    /**
     * @param callable $callback
     *
     * @return void
     */
    public static function serializeUsing($callback)
    {
        static::$serializer = $callback;
    }

    public function __unserialize(array $data): void
    {
        foreach ($data as $property => $value) {
            if ($this instanceof \ArrayAccess) {
                $this[$property] = $value;
            } else {
                $this->{$property} = $value;
            }
        }
    }

    public function unserialize(string $data): void
    {
        $data = @unserialize($data);
        if (!\is_array($data)) {
            return;
        }

        $this->__unserialize($data);
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
