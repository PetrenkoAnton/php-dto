<?php

declare(strict_types=1);

namespace Test;

use Dto\Exception\DeclarationException;
use Dto\Exception\InputDataException;
use Dto\Exception\SetValueException;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\NestedDto;

class NestedDtoTest extends TestCase
{
    private readonly string $aliceName;
    private readonly int $aliceAge;

    private readonly string $bobName;
    private readonly int $bobAge;

    private readonly int $price;
    private readonly string $type;
    private readonly array $info;
    private readonly bool $available;

    private readonly array $data;

    private readonly NestedDto $dto;

    /**
     * @throws InputDataException
     * @throws DeclarationException
     * @throws SetValueException
     */
    public function setUp(): void
    {
        [$this->aliceName, $this->aliceAge] = ['Alice', 25];
        [$this->bobName, $this->bobAge] = ['Bob', 30];
        [$this->price, $this->type, $this->info, $this->available] = [
            999,
            'ticket',
            ['key' => 'value'],
            true
        ];

        $this->data = [
            'persons' => [
                [
                    'name' => $this->aliceName,
                    'age' => $this->aliceAge,
                ],
                [
                    'name' => $this->bobName,
                    'age' => $this->bobAge,
                ],
            ],
            'product' => [
                'price' => $this->price,
                'type' => $this->type,
                'info' => $this->info,
                'available' => $this->available
            ],
        ];

        $this->dto = new NestedDto($this->data);
    }

    /**
     * @group ok
     */
    public function testGetValueSuccess(): void
    {
        $this->assertCount(2, $this->dto->getPersons());

        $this->assertEquals($this->aliceName, $this->dto->getPersons()->first()->getName());
        $this->assertEquals($this->aliceAge, $this->dto->getPersons()->first()->getAge());

        $this->assertEquals($this->aliceName, $this->dto->getPersons()->getItem(0)->getName());
        $this->assertEquals($this->aliceAge, $this->dto->getPersons()->getItem(0)->getAge());

        $this->assertEquals($this->bobName, $this->dto->getPersons()->getItem(1)->getName());
        $this->assertEquals($this->bobAge, $this->dto->getPersons()->getItem(1)->getAge());

        $this->assertEquals($this->price, $this->dto->getProduct()->getPrice());
        $this->assertEquals($this->type, $this->dto->getProduct()->getType());
        $this->assertEquals($this->info, $this->dto->getProduct()->getInfo());
        $this->assertEquals($this->available, $this->dto->getProduct()->isAvailable());

        $this->assertIsArray($this->dto->toArray());
        $this->assertEquals($this->data, $this->dto->toArray());
    }
}
