<?php

declare(strict_types=1);

namespace Test;

use Collection\Exception\CollectionException\InvalidKeyCollectionException;
use Dto\Exception\DtoException;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\NestedDto;
use Tests\Fixtures\PersonDto;

class NestedDtoTest extends TestCase
{
    private string $aliceName;
    private int $aliceAge;

    private string $bobName;
    private int $bobAge;

    private int $price;
    private string $type;
    private array $info;
    private bool $available;

    private array $data;

    private NestedDto $dto;

    /**
     * @throws DtoException
     */
    public function setUp(): void
    {
        [$this->aliceName, $this->aliceAge] = ['Alice', 25];
        [$this->bobName, $this->bobAge] = ['Bob', 30];
        [$this->price, $this->type, $this->info, $this->available] = [
            999,
            'ticket',
            ['key' => 'value'],
            true,
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
                'available' => $this->available,
            ],
        ];

        $this->dto = new NestedDto($this->data);
    }

    /**
     * @throws InvalidKeyCollectionException
     *
     * @group ok
     */
    public function testGetValueSuccess(): void
    {
        $this->assertCount(2, $this->dto->getPersons());

        $personDto = $this->dto->getPersons()->first();
        /** @var PersonDto $personDto */

        $this->assertEquals($this->aliceName, $personDto->getName());
        $this->assertEquals($this->aliceAge, $personDto->getAge());

        $personDto = $this->dto->getPersons()->getItem(0);
        /** @var PersonDto $personDto */

        $this->assertEquals($this->aliceName, $personDto->getName());
        $this->assertEquals($this->aliceAge, $personDto->getAge());

        $personDto = $this->dto->getPersons()->getItem(1);
        /** @var PersonDto $personDto */

        $this->assertEquals($this->bobName, $personDto->getName());
        $this->assertEquals($this->bobAge, $personDto->getAge());

        $this->assertEquals($this->price, $this->dto->getProduct()->getPrice());
        $this->assertEquals($this->type, $this->dto->getProduct()->getType());
        $this->assertEquals($this->info, $this->dto->getProduct()->getInfo());
        $this->assertEquals($this->available, $this->dto->getProduct()->isAvailable());

        $this->assertIsArray($this->dto->toArray());

        $this->assertEquals($this->data, $this->dto->toArray());
    }
}
