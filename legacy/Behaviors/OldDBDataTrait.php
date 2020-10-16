<?php

namespace ByTIC\DataObjects\Legacy\Behaviors;

/**
 * Trait OldDBDataTrait
 * @package ByTIC\DataObjects\Legacy\Behaviors
 */
trait OldDBDataTrait
{
    protected $dbData = [];

    /**
     * @param bool|array $data
     * @deprecated use fillOriginal
     */
    public function writeDBData($data = false)
    {
        $this->dbData = $data;
        $this->fillOriginal($data);
    }

    /**
     * @return array
     * @deprecated use getOriginal
     */
    public function getDBData()
    {
        return $this->getOriginal();
    }

    /**
     * @param $field
     * @deprecated use originalIsEquivalent
     * @return bool
     */
    public function fieldUpdatedFromDb($field)
    {
        return $this->originalIsEquivalent($field) === false;
    }
}
