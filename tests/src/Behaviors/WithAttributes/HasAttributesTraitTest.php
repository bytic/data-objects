<?php

namespace ByTIC\DataObjects\Tests\Behaviors\WithAttributes;

use ByTIC\DataObjects\BaseDto;
use ByTIC\DataObjects\Tests\AbstractTest;

/**
 * Class HasAttributesTraitTest
 * @package ByTIC\DataObjects\Tests\Behaviors\WithAttributes
 */
class HasAttributesTraitTest extends AbstractTest
{
    public function test_getAttributes_empty_array()
    {
        $object = new BaseDto();
        self::assertIsArray($object->getAttributes());
    }


    public function test_setAttribute()
    {
        $object = new BaseDto();
        self::assertFalse($object->hasAttribute('test'));

        $object->setAttribute('test', 'value');
        self::assertTrue($object->hasAttribute('test'));
        self::assertSame('value', $object->getAttribute('test'));
    }
}
