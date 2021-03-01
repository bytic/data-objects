<?php

namespace ByTIC\DataObjects\Tests\Casts;

use ByTIC\DataObjects\Tests\AbstractTest;
use ByTIC\DataObjects\Tests\Fixtures\Models\Books\Book;

/**
 * Class SerializedCollectionTest
 * @package ByTIC\DataObjects\Tests\Casts
 */
class AsArrayObjectTest extends AbstractTest
{
    public function test_cast_empty()
    {
        $book = new Book();
        $book->fill(
            [
                'properties' => ''
            ]
        );

        /** @var \ArrayObject $propertiesValue */
        $propertiesValue = $book->get('properties');
        self::assertInstanceOf(\ArrayObject::class, $propertiesValue);
        self::assertArrayNotHasKey('option1', $propertiesValue, 1);
        self::assertSame('', $book->getAttribute('properties'));

        $propertiesValue['options3'] = 'value3';
        $book->set('properties', $propertiesValue);

        self::assertSame(
            'a:1:{s:8:"options3";s:6:"value3";}',
            $book->getAttribute('properties')
        );
    }

    public function test_cast_invalid()
    {
        $book = new Book();
        $book->fill(
            [
                'properties' => '{789}'
            ]
        );
        $this->expectError();
        $book->get('properties');
    }

    public function test_cast()
    {
        $properties = ['option1' => 1, 'option2' => '2'];
        $propertiesSerialized = serialize($properties);
        $book = new Book();
        $book->fill(
            [
                'properties' => $propertiesSerialized
            ]
        );

        /** @var \ArrayObject $propertiesValue */
        $propertiesValue = $book->get('properties');
        self::assertInstanceOf(\ArrayObject::class, $propertiesValue);
        self::assertSame($propertiesValue['option1'], 1);
        self::assertSame($propertiesValue['option2'], '2');
        self::assertSame($propertiesSerialized, $book->getAttribute('properties'));
//        self::assertSame($propertiesValue->serialize(), $book->getAttribute('properties'));

        $propertiesValue['options3'] = 'value3';
        $book->set('properties', $propertiesValue);

        self::assertSame(
            'a:3:{s:7:"option1";i:1;s:7:"option2";s:1:"2";s:8:"options3";s:6:"value3";}',
            $book->getAttribute('properties')
        );
    }
}
