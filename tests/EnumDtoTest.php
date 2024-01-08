<?php

declare(strict_types=1);

namespace Test;

use Dto\Exception\DeclarationException;
use Dto\Exception\InputDataException;
use Dto\Exception\SetValueException;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\WithEnumDto;

class EnumDtoTest extends TestCase
{
    private readonly int $price;
    private readonly string $color;
    private readonly WithEnumDto $dto;

    /**
     * @throws SetValueException
     * @throws DeclarationException
     * @throws InputDataException
     */
    public function setUp(): void
    {
        [$this->price, $this->color] = [999, 'red'];

        $data = [
            'price' => $this->price,
            'color' => $this->color,
        ];

        $this->dto = new WithEnumDto($data);
    }

    /**
     * @group ok
     */
    public function testGetValueSuccess(): void
    {
        $this->assertEquals($this->price, $this->dto->getPrice());
        $this->assertEquals($this->color, $this->dto->getColor()->value);
    }
//
//    /**
//     * @group ok
//     * @dataProvider dpInvalidGetter
//     */
//    public function testGetInvalidValueThrowsException(string $getter): void
//    {
//        $this->expectException(GetValueException::class);
//        $this->dto->$getter();
//    }
//
//    public function dpInvalidGetter(): array
//    {
//        return [
//            ['getPrice'],
//            ['getType'],
//            ['isAvailable'],
//        ];
//    }
//
//    /**
//     * @group ok
//     * @throws SetValueException
//     * @throws DeclarationException
//     * @throws InputDataException
//     * @dataProvider dpInvalidData
//     */
//    public function testSetInvalidValueThrowsException(array $data, string $msg): void
//    {
//        $this->expectException(SetValueException::class);
//        $this->expectExceptionMessage($msg);
//        new PersonDto($data);
//    }
//
//    public function dpInvalidData(): array
//    {
//        return [
//            //// name
//            [
//                [
//                    'name' => null,
//                    'age' => 25,
//                ],
//                'Dto: Tests\Fixtures\PersonDto | Property: name | Expected type: string | Given type: NULL | Value: null',
//            ],
//            [
//                [
//                    'name' => false,
//                    'age' => 25,
//                ],
//                'Dto: Tests\Fixtures\PersonDto | Property: name | Expected type: string | Given type: boolean | Value: false',
//            ],
//            [
//                [
//                    'name' => 123,
//                    'age' => 25,
//                ],
//                'Dto: Tests\Fixtures\PersonDto | Property: name | Expected type: string | Given type: integer | Value: 123',
//            ],
//            [
//                [
//                    'name' => 123.002,
//                    'age' => 25,
//                ],
//                'Dto: Tests\Fixtures\PersonDto | Property: name | Expected type: string | Given type: double | Value: 123.002',
//            ],
//            [
//                [
//                    'name' => [],
//                    'age' => 25,
//                ],
//                'Dto: Tests\Fixtures\PersonDto | Property: name | Expected type: string | Given type: array | Value: []',
//            ],
//            [
//                [
//                    'name' => new class{},
//                    'age' => 25,
//                ],
//                'Dto: Tests\Fixtures\PersonDto | Property: name | Expected type: string | Given type: object | Value: {}',
//            ],
//
//            //// age
//            [
//                [
//                    'name' => 'Alice',
//                    'age' => null,
//                ],
//                'Dto: Tests\Fixtures\PersonDto | Property: age | Expected type: int | Given type: NULL | Value: null',
//            ],
//            [
//                [
//                    'name' => 'Alice',
//                    'age' => true,
//                ],
//                'Dto: Tests\Fixtures\PersonDto | Property: age | Expected type: int | Given type: boolean | Value: true',
//            ],
//            [
//                [
//                    'name' => 'Alice',
//                    'age' => 123.002,
//                ],
//                'Dto: Tests\Fixtures\PersonDto | Property: age | Expected type: int | Given type: double | Value: 123.002',
//            ],
//            [
//                [
//                    'name' => 'Alice',
//                    'age' => [],
//                ],
//                'Dto: Tests\Fixtures\PersonDto | Property: age | Expected type: int | Given type: array | Value: []',
//            ],
//            [
//                [
//                    'name' => 'Alice',
//                    'age' => new class{},
//                ],
//                'Dto: Tests\Fixtures\PersonDto | Property: age | Expected type: int | Given type: object | Value: {}',
//            ],
//        ];
//    }
}
