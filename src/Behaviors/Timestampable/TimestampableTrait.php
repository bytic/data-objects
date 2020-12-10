<?php

namespace ByTIC\DataObjects\Behaviors\Timestampable;

use DateTime;
use Nip\Utility\Date;

/**
 * Trait TimestampableTrait
 * @package ByTIC\DataObjects\Behaviors\Timestampable
 */
trait TimestampableTrait
{
    protected $timestampableTypes = ['create', 'update'];

    /**
     * @param $attributes
     * @throws \Exception
     */
    public function updatedTimestamps($attributes)
    {
        if (is_string($attributes)) {
            if (in_array($attributes, $this->timestampableTypes)) {
                $attributes = $this->getTimestampAttributes($attributes);
            } else {
                $attributes = [$attributes];
            }
        }
        foreach ($attributes as $attribute) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $this->setModelTimeAttribute($attribute, $this->{$attribute});
        }
    }

    protected function clearTimestamps()
    {
    }

    public function bootTimestampableTrait()
    {
        $this->hookCastableTrait();
    }

    /**
     * @param $attribute
     * @param $value
     * @return false|int|mixed|Date
     * @throws \Exception
     */
    public function setModelTimeAttribute($attribute, $value = null)
    {
        if (\is_string($value) && !empty($value)) {
            $unix = \strtotime($value);
            if ($unix === false) {
                throw new \Exception(
                    sprintf(
                        "Error parsing time value [%s] for field [%s] in object [%s]",
                        $value,
                        $attribute,
                        get_class($this)
                    )
                );
            }
            $value = $unix;
        }

        if (\is_numeric($value)) {
            $value = Date::createFromTimestamp($value);
        }

        if (!\is_object($value)) {
            $value = Date::now();
        }
        return $this->{$attribute} = $value;
    }

    /**
     * Get a fresh timestamp for the model.
     *
     * @return \Nip\Utility\Date
     */
    public function freshTimestamp(): DateTime
    {
        return Date::now();
    }

    /**
     * @param string $type
     * @return array
     */
    public function getTimestampAttributes(string $type = 'create'): array
    {
        if (!in_array($type, ['create', 'update'])) {
            return [];
        }
        $updateTimestamps = $this->getUpdateTimestamps();
        if ($type == 'update') {
            return $updateTimestamps;
        }
        $createTimestamps = $this->getCreateTimestamps();

        return array_merge($createTimestamps, $updateTimestamps);
    }

    /**
     * Get the name of the "created at" column.
     *
     * @return array
     */
    public function getCreateTimestamps(): array
    {
        if (!isset(static::$createTimestamps)) {
            return ['created_at'];
        }
        if (is_string(static::$createTimestamps)) {
            static::$createTimestamps = [static::$createTimestamps];
        }
        return static::$createTimestamps;
    }

    /**
     * Get the name of the "updated at" column.
     *
     * @return array
     */
    public function getUpdateTimestamps(): array
    {
        if (!isset(static::$createTimestamps)) {
            return ['updated_at'];
        }
        if (is_string(static::$updateTimestamps)) {
            static::$updateTimestamps = [static::$updateTimestamps];
        }
        return static::$updateTimestamps;
    }

    /**
     * Get carbon object from timestamp attribute.
     *
     * @param string $attribute Attribute name
     *
     * @return Date|null|string
     */
    public function getTimeFromAttribute(string $attribute)
    {
        $value = $this->{$attribute};

        if (!$this->{$attribute}) {
            return $this->attributes[$attribute] = Date::now()->timestamp;
        }

        if (\is_string($this->attributes[$attribute]) && $timestamp = \strtotime($this->attributes[$attribute])) {
            return $this->attributes[$attribute] = (new Date())->setTimestamp($timestamp);
        }

        return $this->attributes[$attribute];
    }

    protected function hookCastableTrait()
    {
        if (method_exists($this, 'addCast') === false) {
            return;
        }
        $fields = $this->getTimestampAttributes();
        foreach ($fields as $field) {
            if ($this->hasCast($field)) {
                continue;
            }
            $this->addCast($field,'datetime');
        }
    }
}
