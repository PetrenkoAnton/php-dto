<?php

declare(strict_types=1);

namespace Test;

use Dto\Exception\SetValueEnumException;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\ColorEnum;
use Tests\Fixtures\WithEnumDto;

class EnumDtoTest extends TestCase
{
    /**
     * @group ok
     */
    public function testGetValueSuccess(): void
    {
        $dto = new WithEnumDto(['color' => $color = 'red']);

        $this->assertEquals(ColorEnum::class, \get_class($dto->getColor()));
        $this->assertEquals($color, $dto->getColor()->value);
    }

    /**
     * @group ok
     * @dataProvider dpInvalidData
     */
    public function testSetInvalidValueThrowsException(array $data, string $msg): void
    {
        $this->expectException(SetValueEnumException::class);
        $this->expectExceptionMessage($msg);
        new WithEnumDto($data);
    }

    public function dpInvalidData(): array
    {
        return [
            [
                [
                    'color' => 'green',
                ],
                'Dto: Tests\Fixtures\WithEnumDto | Property: color | Enum: Tests\Fixtures\ColorEnum | Expected values: red, black, white | Given type: string | Value: "green"',
            ],
            [
                [
                    'color' => 'blue',
                ],
                'Dto: Tests\Fixtures\WithEnumDto | Property: color | Enum: Tests\Fixtures\ColorEnum | Expected values: red, black, white | Given type: string | Value: "blue"',
            ],
            [
                [
                    'color' => '',
                ],
                'Dto: Tests\Fixtures\WithEnumDto | Property: color | Enum: Tests\Fixtures\ColorEnum | Expected values: red, black, white | Given type: string | Value: ""',
            ],
        ];
    }
}
