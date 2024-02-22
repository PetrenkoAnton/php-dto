<?php

declare(strict_types=1);

namespace Test;

use PHPUnit\Framework\TestCase;
use Tests\Fixtures\NestedNullableDto;

class NestedNullableDtoTest extends TestCase
{
    /**
     * @group ok
     * @dataProvider dp
     */
    public function testGetNullableValue(array $data): void
    {
        $dto = new NestedNullableDto($data);
        $this->assertEquals($data, $dto->toArray());
    }

    public static function dp(): array
    {
        return [
            [
                [
                    'type' => null,
                    'error' => null,
                ],
            ],
            [
                [
                    'type' => 'type',
                    'error' => null,
                ],
            ],
            [
                [
                    'type' => null,
                    'error' => [
                        'message' => 'Error message',
                        'code' => 0,
                    ],
                ],
            ],
        ];
    }
}
