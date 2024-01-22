<?php

declare(strict_types=1);

namespace Test;

use Collection\Arrayable;
use Dto\Exception\DtoException;
use Dto\Exception\DtoException\HandleDtoException\GetValueException;
use Dto\Exception\DtoException\SetupDtoException\InputDataException;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\InfoArrayable;
use Tests\Fixtures\PersonDto;
use Tests\Fixtures\ProductDto;

use function is_a;
use function sprintf;

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
     * @dataProvider dpArrayableData
     */
    public function testGetArrayableValueSuccess(array | Arrayable $info): void
    {
        $data = [
            'price' => 999,
            'type' => 'ticket',
            'available' => true,
        ];

        $data += ['info' => $info];

        $dto = new ProductDto($data);

        $expected = is_a($info, Arrayable::class) ? $info->toArray() : $info;

        $this->assertEquals($expected, $dto->getInfo());
    }

    public function dpArrayableData(): array
    {
        return [
            [
                [

                ],
            ],
            [
                [
                    'key' => 'value',
                ],
            ],
            [
                new InfoArrayable(),
            ],
        ];
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

    /**
     * @throws DtoException
     *
     * @group ok
     * @dataProvider dpIncompleteData
     */
    public function testSetIncompleteDataThrowsInputDataException(array $data, string $property): void
    {
        $this->expectException(InputDataException::class);
        $this->expectExceptionMessage(
            sprintf('Dto: Tests\Fixtures\ProductDto | Property: %s | Err: No data', $property),
        );
        $this->expectExceptionCode(202);
        new ProductDto($data);
    }

    public function dpIncompleteData(): array
    {
        return [
            [
                [
                    'price' => 999,
                    'type' => 'ticket',
                    'available' => true,
                ],
                'info',
            ],
            [
                [
                    'type' => 'ticket',
                    'price' => 999,
                    'available' => true,
                ],
                'info',
            ],
            [
                [
                    'type' => 'ticket',
                    'available' => true,
                ],
                'price',
            ],
            [
                [
                    'available' => true,
                ],
                'price',
            ],
        ];
    }

    /**
     * @throws GetValueException
     *
     * @group ok
     * @dataProvider dpInvalidMethod
     */
    public function testInvalidMethodsThrowsDtoException(string $method, mixed $argument = null): void
    {
        $this->expectException(GetValueException::class);
        $this->expectExceptionMessage(
            sprintf('Dto: Tests\Fixtures\PersonDto | Unexpected method: %s', $method),
        );
        $this->expectExceptionCode(301);

        $this->dto->{$method}($argument);
    }

    public function dpInvalidMethod(): array
    {
        return [
            [
                'get',
                5,
            ],
            [
                'delete',
            ],
            [
                'setValue',
                'random string',
            ],
            [
                'value',
                new class {
                },
            ],
        ];
    }
}
