<?php

declare(strict_types=1);

namespace Test;

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

    public static function dpInvalidGetter(): array
    {
        return [
            ['getPrice'],
            ['getType'],
            ['isAvailable'],
        ];
    }
}
