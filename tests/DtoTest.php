<?php

declare(strict_types=1);

namespace Test;

use Dto\Exception\DtoException;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\PersonDto;
use Tests\Fixtures\ProductDto;

class DtoTest extends TestCase
{
    private string $name;
    private int $age;
    private PersonDto $dto;

    /**
     * @throws DtoException
     */
    public function setUp(): void
    {
        [$this->name, $this->age] = ['Alice', 25];

        $data = [
            'name' => $this->name,
            'age' => $this->age,
        ];

        $this->dto = new PersonDto($data);
    }

    /**
     * @group ok
     */
    public function testGetValueSuccess(): void
    {
        $this->assertEquals($this->name, $this->dto->getName());
        $this->assertEquals($this->age, $this->dto->getAge());
    }

    /**
     * @group ok
     * @dataProvider dpInvalidGetters
     */
    public function testGetInvalidValueThrowsException(string $getter, string $property): void
    {
        $this->expectException(DtoException::class);
        $this->expectExceptionMessage("Dto: Tests\Fixtures\PersonDto | Unexpected property: $property");
        $this->expectExceptionCode(301);
        $this->dto->$getter();
    }

    /**
     * @return string[][]
     */
    public function dpInvalidGetters(): array
    {
        return [
            ['getPrice', 'price'],
            ['getType', 'type'],
            ['isAvailable', 'available'],
        ];
    }

    /**
     * @throws DtoException
     *
     * @group ok
     * @dataProvider dpInvalidData
     */
    public function testSetInvalidValueThrowsDtoException(array $data, string $message): void
    {
        $this->expectException(DtoException::class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode(203);
        new PersonDto($data);
    }

    public function dpInvalidData(): array
    {
        return [
            [
                [
                    'name' => null,
                    'age' => 25,
                ],
                // phpcs:ignore
                'Dto: Tests\Fixtures\PersonDto | Property: name | Expected type: string | Given type: NULL | Value: null',
            ],
            [
                [
                    'name' => 'Alice',
                    'age' => false,
                ],
                // phpcs:ignore
                'Dto: Tests\Fixtures\PersonDto | Property: age | Expected type: int | Given type: boolean | Value: false',
            ],
            [
                [
                    'name' => 123,
                    'age' => 25,
                ],
                // phpcs:ignore
                'Dto: Tests\Fixtures\PersonDto | Property: name | Expected type: string | Given type: integer | Value: 123',
            ],
            [
                [
                    'name' => 'Alice',
                    'age' => 25.002,
                ],
                // phpcs:ignore
                'Dto: Tests\Fixtures\PersonDto | Property: age | Expected type: int | Given type: double | Value: 25.002',
            ],
            [
                [
                    'name' => [],
                    'age' => 25,
                ],
                // phpcs:ignore
                'Dto: Tests\Fixtures\PersonDto | Property: name | Expected type: string | Given type: array | Value: []',
            ],
            [
                [
                    'name' => 'Alice',
                    'age' => new class {
                        public int $age = 25;
                    },
                ],
                // phpcs:ignore
                'Dto: Tests\Fixtures\PersonDto | Property: age | Expected type: int | Given type: object | Value: {"age":25}',
            ],
        ];
    }

    /**
     * @throws DtoException
     *
     * @group ok
     * @dataProvider dpInvalidArrayData
     */
    public function testSetInvalidArrayValueThrowsDtoException(int | bool $info, string $message): void
    {
        $data = [
            'price' => 999,
            'type' => 'ticket',
            'available' => true,
        ];

        $data += ['info' => $info];

        $this->expectException(DtoException::class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode(203);
        new ProductDto($data);
    }

    public function dpInvalidArrayData(): array
    {
        return [
            [
                123,
                // phpcs:ignore
                'Dto: Tests\Fixtures\ProductDto | Property: info | Expected type: array | Given type: integer | Value: 123',
            ],
            [
                true,
                // phpcs:ignore
                'Dto: Tests\Fixtures\ProductDto | Property: info | Expected type: array | Given type: boolean | Value: true',
            ],
        ];
    }
}
