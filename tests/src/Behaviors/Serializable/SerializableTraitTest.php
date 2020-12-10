<?php

namespace ByTIC\DataObjects\Tests\Behaviors\Serializable;

use ByTIC\DataObjects\Tests\AbstractTest;
use ByTIC\DataObjects\Tests\Fixtures\Dto\Serializable;

/**
 * Class SerializableTraitTest
 * @package ByTIC\DataObjects\Tests\Behaviors\Serializable
 */
class SerializableTraitTest extends AbstractTest
{
    public function test_serialize()
    {
        $object = new Serializable();
        $object->set('test', 'value');

        $serialized = serialize($object);
        self::assertStringContainsString(
            'O:49:"ByTIC\DataObjects\Tests\Fixtures\Dto\Serializable"',
            $serialized
        );

        $recovered = unserialize($serialized);
        self::assertInstanceOf(Serializable::class, $recovered);
        self::assertEquals($object, $recovered);
    }
}
