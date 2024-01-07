<?php

declare(strict_types=1);

namespace Test;

use Dto\Exception\DeclarationException;
use Dto\Exception\SetValueException;
use Dto\Exception\GetValueException;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\PersonDto;

class DtoTest extends TestCase
{
    private readonly string $name;
    private readonly int $age;
    private readonly PersonDto $dto;

    /**
     * @throws SetValueException
     * @throws DeclarationException
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
     * @dataProvider dpInvalidGetter
     */
    public function testGetInvalidValueThrowsException(string $getter): void
    {
        $this->expectException(GetValueException::class);
        $this->dto->$getter();
    }

    public function dpInvalidGetter(): array
    {
        return [
            ['getPrice'],
            ['getType'],
            ['isAvailable'],
        ];
    }

    /**
     * @group ok
     * @dataProvider dpInvalidData
     */
    public function testSetInvalidValueThrowsException(array $data, string $msg): void
    {
        $this->expectException(SetValueException::class);
        $this->expectExceptionMessage($msg);
        new PersonDto($data);
    }

    public function dpInvalidData(): array
    {
        return [
            //// name
            [
                [
                    'name' => null,
                    'age' => 25,
                ],
                'Dto: Tests\Fixtures\PersonDto | Property: name | Expected type: string | Given type: NULL | Value: null',
            ],
            [
                [
                    'name' => false,
                    'age' => 25,
                ],
                'Dto: Tests\Fixtures\PersonDto | Property: name | Expected type: string | Given type: boolean | Value: false',
            ],
            [
                [
                    'name' => 123,
                    'age' => 25,
                ],
                'Dto: Tests\Fixtures\PersonDto | Property: name | Expected type: string | Given type: integer | Value: 123',
            ],
            [
                [
                    'name' => 123.002,
                    'age' => 25,
                ],
                'Dto: Tests\Fixtures\PersonDto | Property: name | Expected type: string | Given type: double | Value: 123.002',
            ],
            [
                [
                    'name' => [],
                    'age' => 25,
                ],
                'Dto: Tests\Fixtures\PersonDto | Property: name | Expected type: string | Given type: array | Value: []',
            ],
            [
                [
                    'name' => new class{},
                    'age' => 25,
                ],
                'Dto: Tests\Fixtures\PersonDto | Property: name | Expected type: string | Given type: object | Value: {}',
            ],

            //// age
            [
                [
                    'name' => 'Alice',
                    'age' => null,
                ],
                'Dto: Tests\Fixtures\PersonDto | Property: age | Expected type: int | Given type: NULL | Value: null',
            ],
            [
                [
                    'name' => 'Alice',
                    'age' => true,
                ],
                'Dto: Tests\Fixtures\PersonDto | Property: age | Expected type: int | Given type: boolean | Value: true',
            ],
            [
                [
                    'name' => 'Alice',
                    'age' => 123.002,
                ],
                'Dto: Tests\Fixtures\PersonDto | Property: age | Expected type: int | Given type: double | Value: 123.002',
            ],
            [
                [
                    'name' => 'Alice',
                    'age' => [],
                ],
                'Dto: Tests\Fixtures\PersonDto | Property: age | Expected type: int | Given type: array | Value: []',
            ],
            [
                [
                    'name' => 'Alice',
                    'age' => new class{},
                ],
                'Dto: Tests\Fixtures\PersonDto | Property: age | Expected type: int | Given type: object | Value: {}',
            ],
        ];
    }
}
