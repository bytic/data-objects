<?php

namespace ByTIC\DataObjects;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use DateTimeInterface;

/**
 * Class ValueCaster
 * @package ByTIC\DataObjects
 */
class ValueCaster
{
    /**
     * The built-in, primitive cast types
     *
     * @var array
     */
    public static $primitiveTypes = [
        'array',
        'bool',
        'boolean',
        'collection',
        'custom_datetime',
        'date',
        'datetime',
        'decimal',
        'double',
        'float',
        'int',
        'integer',
        'json',
        'object',
        'real',
        'string',
        'timestamp',
    ];

    public static function as($value, string $type)
    {
        switch ($type) {
            case 'int':
            case 'integer':
                return (int)$value;

            case 'real':
            case 'float':
            case 'double':
                return static::asFloat($value);

            case 'decimal':
                return static::asDecimal($value);

            case 'string':
            case 'str':
                return static::asString($value);

            case 'bool':
            case 'boolean':
                return (bool) $value;

            case 'date':
                return static::asDate($value);

            case 'datetime':
            case 'custom_datetime':
                return static::asDateTime($value);

            case 'timestamp':
                return static::asTimestamp($value);
        }
    }

    /**
     * Cast given value to a string
     *
     * @param mixed $value
     *
     * @return string
     */
    public static function asString($value): string
    {
        return (string)$value;
    }

    /**
     * Cast given value to a string
     *
     * @param mixed $value
     *
     * @return string
     */
    public static function asInteger($value): string
    {
        return (int)$value;
    }

    /**
     * Decode the given float.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public static function asFloat($value)
    {
        switch ((string) $value) {
            case 'Infinity':
                return INF;
            case '-Infinity':
                return -INF;
            case 'NaN':
                return NAN;
            default:
                return (float) $value;
        }
    }

    /**
     * Return a decimal as string.
     *
     * @param  float  $value
     * @param  int  $decimals
     * @return string
     */
    public static function asDecimal($value, $decimals = 3)
    {
        return number_format($value, $decimals, '.', '');
    }

    /**
     * Cast given value to a DateTime instance
     *
     * @param string|null $value
     *
     * @return Carbon|DateTimeInterface
     */
    public static function asDate($value)
    {
        return Carbon::parse($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function asDateTime($value)
    {
        // If this value is already a Carbon instance, we shall just return it as is.
        // This prevents us having to re-instantiate a Carbon instance when we know
        // it already is one, which wouldn't be fulfilled by the DateTime check.
        if ($value instanceof CarbonInterface) {
            return Carbon::instance($value);
        }

        // If the value is already a DateTime instance, we will just skip the rest of
        // these checks since they will be a waste of time, and hinder performance
        // when checking the field. We will just return the DateTime right away.
        if ($value instanceof DateTimeInterface) {
            return Carbon::parse(
                $value->format('Y-m-d H:i:s.u'),
                $value->getTimezone()
            );
        }

        // If this value is an integer, we will assume it is a UNIX timestamp's value
        // and format a Carbon object from this timestamp. This allows flexibility
        // when defining your date fields as they might be UNIX timestamps here.
        if (is_numeric($value)) {
            return Carbon::createFromTimestamp($value);
        }

        return Carbon::parse($value);
    }
}
