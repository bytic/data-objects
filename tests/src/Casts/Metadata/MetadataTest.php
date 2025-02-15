<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Casts\Metadata;

use PHPUnit\Framework\TestCase;

/**
 *
 */
class MetadataTest extends TestCase
{
    /**
     * @param $merged
     * @param $result
     * @return void
     * @dataProvider data_merge
     */
    public function test_merge($merged, $result)
    {
        $metadata1 = new Metadata(['key' => 'value']);
        $metadata2 = new Metadata($merged);
        $metadata1->merge($metadata2);

        self::assertSame($result, $metadata1->getArrayCopy());
    }

    public function data_merge(): array
    {
        return [
            [
                ['key2' => 'value2'],
                ['key' => 'value', 'key2' => 'value2'],
            ],
            [
                ['key' => 'value2'],
                ['key' => 'value2'],
            ],
            [
                ['key' => ''],
                ['key' => 'value'],
            ],
            [
                ['key' => null],
                ['key' => 'value'],
            ],
        ];
    }
}
