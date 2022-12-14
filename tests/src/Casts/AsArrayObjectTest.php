<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Tests\Casts;

use ByTIC\DataObjects\Tests\AbstractTest;
use ByTIC\DataObjects\Tests\Fixtures\Models\Books\Book;

/**
 * Class SerializedCollectionTest.
 */
class AsArrayObjectTest extends AbstractTest
{
    /**
     * @dataProvider data_cast_values
     */
    public function testCastValues($string, $return)
    {
        $book = new Book();
        $book->fill(['properties' => $string]);

        /** @var \ArrayObject $propertiesValue */
        $propertiesValue = $book->get('properties');
        self::assertInstanceOf(\ArrayObject::class, $propertiesValue);

        self::assertSame($return, $propertiesValue->getArrayCopy());
    }

    public function data_cast_values()
    {
        return [
            [null, []],
            ['', []],
            ['N;', []],
            ['a:0:{}', []],
            ['b:0;', []],
            ['a:1:{s:8:"currency";s:3:"RON";}', ['currency' => 'RON']],
            [serialize(['test' => 1]), ['test' => 1]],
        ];
    }

    public function testCastNull()
    {
        $book = new Book();
        $book->fill(
            [
                'properties' => null,
            ]
        );

        /** @var \ArrayObject $propertiesValue */
        $propertiesValue = $book->get('properties');
        self::assertInstanceOf(\ArrayObject::class, $propertiesValue);
        self::assertArrayNotHasKey('option1', $propertiesValue, 'Should not have option1');
        self::assertNull($book->getAttribute('properties'));

        $propertiesValue['options3'] = 'value3';
        $book->set('properties', $propertiesValue);

        self::assertSame(
            'a:1:{s:8:"options3";s:6:"value3";}',
            $book->getAttribute('properties')
        );
    }

    public function testCastString()
    {
        $book = new Book();
        $book->fill(
            [
                'properties' => 'N;',
            ]
        );

        /** @var \ArrayObject $propertiesValue */
        $propertiesValue = $book->get('properties');
        self::assertInstanceOf(\ArrayObject::class, $propertiesValue);
        self::assertArrayNotHasKey('option1', $propertiesValue, 'Should not have option1');
        self::assertSame('N;', $book->getAttribute('properties'));

        $propertiesValue['options3'] = 'value3';
        $book->set('properties', $propertiesValue);

        self::assertSame(
            'a:1:{s:8:"options3";s:6:"value3";}',
            $book->getAttribute('properties')
        );
    }

    public function testCastInvalid()
    {
        $book = new Book();
        $book->fill(
            [
                'properties' => '{789}',
            ]
        );
        $this->expectError();
        $book->get('properties');
    }

    public function testCast()
    {
        $properties = ['option1' => 1, 'option2' => '2'];
        $propertiesSerialized = serialize($properties);
        $book = new Book();
        $book->fill(
            [
                'properties' => $propertiesSerialized,
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
