<?php

namespace ByTIC\DataObjects\Behaviors\Castable;

use ByTIC\DataObjects\Casts\Castable;
use ByTIC\DataObjects\Casts\CastsInboundAttributes;
use ByTIC\DataObjects\Exceptions\InvalidCastException;
use ByTIC\DataObjects\ValueCaster;

/**
 * Trait CastableTrait.
 */
trait CastableTrait
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * The attributes casted values.
     *
     * @var array
     */
    protected $castsValueCache = [];

    /**
     * The attributes that have been cast using custom classes.
     *
     * @var array
     */
    protected $classCastCache = [];

    /**
     * @return mixed
     */
    public function transformValue($key, $value)
    {
        if (isset($this->castsValueCache[$key]) && null != $value) {
            return $this->castsValueCache[$key];
        }

        // If the attribute exists within the cast array, we will convert it to
        // an appropriate native PHP type dependent upon the associated value
        // given with the key in the pair. Dayle made this comment line up.
        if ($this->hasCast($key)) {
            $value = $this->castValue($key, $value);
        }
        $this->castsValueCache[$key] = $value;

        return $value;
    }

    /**
     * @return mixed
     */
    public function transformInboundValue($key, $value)
    {
        unset($this->castsValueCache[$key]);

        if ($value && $this->isDateCastable($key)) {
            $value = ValueCaster::asDateTime($value);

            return $value instanceof \DateTime ? $value->format('Y-m-d H:i:s') : null;
        }
        if ($this->isClassCastable($key)) {
            return $this->transformClassCastableAttribute($key, $value);
        }

        return $value;
    }

    /**
     * Determine whether an attribute should be cast to a native type.
     *
     * @param string            $key
     * @param array|string|null $types
     */
    public function hasCast($key, $types = null): bool
    {
        if (\array_key_exists($key, $this->getCasts())) {
            return $types ? \in_array($this->getCastType($key), (array) $types, true) : true;
        }

        return false;
    }

    /**
     * Get the casts array.
     */
    public function getCasts(): array
    {
        return $this->casts;
    }

    public function addCast($attribute, $cast): self
    {
        $this->casts[$attribute] = $cast;

        return $this;
    }

    /**
     * Cast an attribute to a native PHP type.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    protected function castValue(string $key, $value)
    {
        $castType = $this->getCastType($key);

        $isPrimitiveType = \in_array($castType, ValueCaster::$primitiveTypes);

        if (null === $value && $isPrimitiveType) {
            return $value;
        }

        if ($isPrimitiveType) {
            return ValueCaster::as($value, $castType);
        }

        if ($this->isClassCastable($key)) {
            return $this->getClassCastableAttributeValue($key, $value);
        }

        return $value;
    }

    protected function transformClassCastableAttribute($key, $value)
    {
        $caster = $this->resolveCasterClass($key);
        $responseValues = $this->normalizeCastClassResponse(
            $key,
            $caster->set($this, $key, $value, $this->attributes)
        );
        $return = null;
        if (isset($responseValues[$key])) {
            $return = $responseValues[$key];
            unset($responseValues[$key]);
        }

        if ($caster instanceof CastsInboundAttributes || !\is_object($value)) {
            unset($this->classCastCache[$key]);
        } else {
            $this->classCastCache[$key] = $value;
        }

        return $return;
    }

    /**
     * Cast the given attribute using a custom cast class.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    protected function getClassCastableAttributeValue(string $key, $value)
    {
        if (isset($this->classCastCache[$key])) {
            return $this->classCastCache[$key];
        } else {
            $caster = $this->resolveCasterClass($key);

            $value = $caster instanceof CastsInboundAttributes
                ? $value
                : $caster->get($this, $key, $value, $this->attributes);

            if ($caster instanceof CastsInboundAttributes || !\is_object($value)) {
                unset($this->classCastCache[$key]);
            } else {
                $this->classCastCache[$key] = $value;
            }

            return $value;
        }
    }

    /**
     * Get the type of cast for a model attribute.
     */
    protected function getCastType(string $key): string
    {
        if ($this->isCustomDateTimeCast($this->getCasts()[$key])) {
            return 'custom_datetime';
        }

        if ($this->isDecimalCast($this->getCasts()[$key])) {
            return 'decimal';
        }

        return trim(strtolower($this->getCasts()[$key]));
    }

    /**
     * Determine if the cast type is a custom date time cast.
     */
    protected function isCustomDateTimeCast(string $cast): bool
    {
        return 0 === strncmp($cast, 'date:', 5) ||
            0 === strncmp($cast, 'datetime:', 9);
    }

    /**
     * Determine if the cast type is a decimal cast.
     */
    protected function isDecimalCast(string $cast): bool
    {
        return 0 === strncmp($cast, 'decimal:', 8);
    }

    /**
     * Determine whether a value is Date / DateTime castable for inbound manipulation.
     */
    protected function isDateCastable(string $key): bool
    {
        return $this->hasCast($key, ['date', 'datetime']);
    }

    /**
     * Determine whether a value is JSON castable for inbound manipulation.
     */
    protected function isJsonCastable(string $key): bool
    {
        return $this->hasCast(
            $key,
            [
                'array',
                'json',
                'object',
                'collection',
                'encrypted:array',
                'encrypted:collection',
                'encrypted:json',
                'encrypted:object',
            ]
        );
    }

    /**
     * Determine if the given key is cast using a custom class.
     */
    protected function isClassCastable(string $key): bool
    {
        if (!\array_key_exists($key, $this->getCasts())) {
            return false;
        }

        $castType = $this->parseCasterClass($this->getCasts()[$key]);

        if (\in_array($castType, ValueCaster::$primitiveTypes)) {
            return false;
        }

        if (class_exists($castType)) {
            return true;
        }

        throw new InvalidCastException($this, $key, $castType);
    }

    /**
     * Resolve the custom caster class for a given key.
     *
     * @return mixed
     */
    protected function resolveCasterClass(string $key)
    {
        $castType = $this->getCasts()[$key];

        $arguments = [];

        if (\is_string($castType) && false !== strpos($castType, ':')) {
            $segments = explode(':', $castType, 2);

            $castType = $segments[0];
            $arguments = explode(',', $segments[1]);
        }

        if (is_subclass_of($castType, Castable::class)) {
            $castType = $castType::castUsing($arguments);
        }

        if (\is_object($castType)) {
            return $castType;
        }

        return new $castType(...$arguments);
    }

    /**
     * Parse the given caster class, removing any arguments.
     */
    protected function parseCasterClass(string $class): string
    {
        return false === strpos($class, ':')
            ? $class
            : explode(':', $class, 2)[0];
    }

    /**
     * Normalize the response from a custom class caster.
     *
     * @param mixed $value
     */
    protected function normalizeCastClassResponse(string $key, $value): array
    {
        return \is_array($value) ? $value : [$key => $value];
    }
}
