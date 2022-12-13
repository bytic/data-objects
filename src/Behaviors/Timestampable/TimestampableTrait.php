<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Behaviors\Timestampable;

use Nip\Utility\Date;

/**
 * Trait TimestampableTrait.
 */
trait TimestampableTrait
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     *           public $timestamps = true;
     */
    protected $timestampableTypes = ['create', 'update'];

    /**
     * @throws \Exception
     */
    public function updatedTimestamps($attributes)
    {
        if (false === $this->usesTimestamps()) {
            return;
        }

        if (\is_string($attributes)) {
            if (\in_array($attributes, $this->timestampableTypes)) {
                $attributes = $this->getTimestampAttributes($attributes);
            } else {
                $attributes = [$attributes];
            }
        }
        foreach ($attributes as $attribute) {
            /* @noinspection PhpUnhandledExceptionInspection */
            $this->setModelTimeAttribute($attribute, $this->{$attribute});
        }
    }

    /**
     * Determine if the model uses timestamps.
     */
    public function usesTimestamps(): bool
    {
        static $timestamps = null;
        if (\is_bool($timestamps)) {
            return $timestamps;
        }
        if (property_exists($this, 'timestamps')) {
            $timestamps = $this->timestamps;

            return $timestamps;
        }
        if (property_exists($this, 'createTimestamps')) {
            $timestamps = true;

            return $timestamps;
        }
        if (property_exists($this, 'updateTimestamps')) {
            $timestamps = true;

            return $timestamps;
        }

        $timestamps = $this->usesTimestampsDefault();

        return $timestamps;
    }

    /**
     * @return false
     */
    protected function usesTimestampsDefault()
    {
        return false;
    }

    public function bootTimestampableTrait()
    {
        if (false === $this->usesTimestamps()) {
            return;
        }
        $this->hookCastableTrait();
    }

    /**
     * @return false|int|mixed|Date
     *
     * @throws \Exception
     */
    public function setModelTimeAttribute($attribute, $value = null)
    {
        if (\is_string($value) && !empty($value)) {
            $unix = strtotime($value);
            if (false === $unix) {
                throw new \Exception(sprintf('Error parsing time value [%s] for field [%s] in object [%s]', $value, $attribute, static::class));
            }
            $value = $unix;
        }

        if (is_numeric($value)) {
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
    public function freshTimestamp(): \DateTime
    {
        return Date::now();
    }

    public function getTimestampAttributes(string $type = 'create'): array
    {
        if (!\in_array($type, ['create', 'update'])) {
            return [];
        }
        $updateTimestamps = $this->getUpdateTimestamps();
        if ('update' == $type) {
            return $updateTimestamps;
        }
        $createTimestamps = $this->getCreateTimestamps();

        return array_merge($createTimestamps, $updateTimestamps);
    }

    /**
     * Get the name of the "created at" column.
     */
    public function getCreateTimestamps(): array
    {
        if (!isset(static::$createTimestamps)) {
            return true === $this->timestamps ? ['created_at'] : [];
        }
        if (\is_string(static::$createTimestamps)) {
            static::$createTimestamps = [static::$createTimestamps];
        }

        return static::$createTimestamps;
    }

    /**
     * Get the name of the "updated at" column.
     */
    public function getUpdateTimestamps(): array
    {
        if (!isset(static::$updateTimestamps)) {
            return true === $this->timestamps ? ['updated_at'] : [];
        }
        if (\is_string(static::$updateTimestamps)) {
            static::$updateTimestamps = [static::$updateTimestamps];
        }

        return static::$updateTimestamps;
    }

    /**
     * Get carbon object from timestamp attribute.
     *
     * @param string $attribute Attribute name
     *
     * @return Date|string|null
     */
    public function getTimeFromAttribute(string $attribute)
    {
        $value = $this->{$attribute};

        if (!$this->{$attribute}) {
            return $this->attributes[$attribute] = Date::now()->timestamp;
        }

        if (\is_string($this->attributes[$attribute]) && $timestamp = strtotime($this->attributes[$attribute])) {
            return $this->attributes[$attribute] = (new Date())->setTimestamp($timestamp);
        }

        return $this->attributes[$attribute];
    }

    protected function hookCastableTrait()
    {
        if (false === method_exists($this, 'addCast')) {
            return;
        }
        $fields = $this->getTimestampAttributes();
        foreach ($fields as $field) {
            if ($this->hasCast($field)) {
                continue;
            }
            $this->addCast($field, 'datetime');
        }
    }
}
