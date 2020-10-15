<?php

use ByTIC\DataObjects\Tests\Fixtures\Dto\ObjectWithGetSet;

/**
 * Class AccessorsTraitBench
 * @Iterations(5)
 * @Revs(100)
 * @BeforeMethods({"init"})
 */
class AccessorsTraitBench
{
    protected $objectWithData;
    protected $object;

    public function bench_GetProperty_DynamicProperty()
    {
        $this->object->name;
    }

    public function bench_GetterMethod_DynamicProperty()
    {
        $this->object->getName();
    }

    public function bench_GetProperty_PrivateProperty()
    {
        $this->object->title;
    }

    public function bench_GetterMethod_PrivateProperty()
    {
        $this->object->getTitle();
    }

    public function init()
    {
        $this->objectWithData = new ObjectWithGetSet(['name' => 'test']);
        $this->object = new ObjectWithGetSet();
    }
}
