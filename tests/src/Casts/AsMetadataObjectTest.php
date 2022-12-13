<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Tests\Casts;

use ByTIC\DataObjects\Casts\Metadata\Metadata;
use ByTIC\DataObjects\Tests\AbstractTest;
use ByTIC\DataObjects\Tests\Fixtures\Models\Books\Book;
use ByTIC\DataObjects\Tests\Fixtures\Models\Books\BookOptions;

/**
 * Class SerializedCollectionTest.
 */
class AsMetadataObjectTest extends AbstractTest
{
    /**
     * @dataProvider data_cast_values
     */
    public function testCastValues($string, $return)
    {
        $book = new Book();
        $book->fill(['metadata' => $string]);

        /** @var Metadata $propertiesValue */
        $propertiesValue = $book->get('metadata');
        self::assertInstanceOf(\ByTIC\DataObjects\Casts\Metadata\Metadata::class, $propertiesValue);

        self::assertSame($return, $propertiesValue->getArrayCopy());
    }

    /**
     * @return array[]
     */
    public function data_cast_values()
    {
        return [
            [null, []],
            ['', []],
            ['{}', []],
            [json_encode(['currency' => 'RON']), ['currency' => 'RON']],
            [json_encode(['test' => 1]), ['test' => 1]],
        ];
    }

    public function testCastNull()
    {
        $book = new Book();
        $book->fill(
            [
                'metadata' => null,
            ]
        );

        /** @var Metadata $propertiesValue */
        $propertiesValue = $book->get('metadata');
        self::assertInstanceOf(Metadata::class, $propertiesValue);
        self::assertArrayNotHasKey('option1', $propertiesValue, "Should not have option1");
        self::assertNull($book->getAttribute('metadata'));

        $propertiesValue['options3'] = 'value3';
        $book->set('metadata', $propertiesValue);

        self::assertSame(
            '{"options3":"value3"}',
            $book->getAttribute('metadata')
        );
    }

    public function testCastString()
    {
        $book = new Book();
        $book->fill(
            [
                'metadata' => '{}',
            ]
        );

        /** @var Metadata $propertiesValue */
        $propertiesValue = $book->get('metadata');
        self::assertInstanceOf(Metadata::class, $propertiesValue);
        self::assertArrayNotHasKey('option1', $propertiesValue, "Should not have option1");
        self::assertSame('{}', $book->getAttribute('metadata'));

        $propertiesValue['options3'] = 'value3';
        $book->set('metadata', $propertiesValue);

        self::assertSame(
            '{"options3":"value3"}',
            $book->getAttribute('metadata')
        );
    }

    public function testCastInvalid()
    {
        $book = new Book();
        $book->fill(
            [
                'metadata' => '{789}',
            ]
        );
        $this->expectException(\InvalidArgumentException::class);
        $book->get('metadata');
    }

    public function testCastWithCustomClass()
    {
        $book = new Book();

        $options = $book->get('options');
        self::assertInstanceOf(BookOptions::class, $options);
    }

    public function testCast()
    {
        $properties = ['option1' => 1, 'option2' => '2', 'option3' => ['opt33' => '33']];
        $propertiesSerialized = json_encode($properties);
        $book = new Book();
        $book->fill(
            [
                'metadata' => $propertiesSerialized,
            ]
        );

        /** @var Metadata $propertiesValue */
        $propertiesValue = $book->get('metadata');
        self::assertInstanceOf(Metadata::class, $propertiesValue);
        self::assertSame($propertiesValue->get('option1'), 1);
        self::assertSame($propertiesValue->get('option3.opt33'), '33');
        self::assertSame($propertiesSerialized, $book->getAttribute('metadata'));

        $book->metadata->set('option2', '22');
        $book->metadata->set('option3.opt33', '333');

        self::assertSame(
            '{"option1":1,"option2":"22","option3":{"opt33":"333"}}',
            $book->getAttribute('metadata')
        );
    }
}
