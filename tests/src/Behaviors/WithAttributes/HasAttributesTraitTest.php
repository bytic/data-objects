<?php

namespace ByTIC\DataTransferObject\Tests\Behaviors\WithAttributes;

use ByTIC\DataTransferObject\BaseDto;
use ByTIC\DataTransferObject\Tests\AbstractTest;

/**
 * Class HasAttributesTraitTest
 * @package ByTIC\DataTransferObject\Tests\Behaviors\WithAttributes
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
