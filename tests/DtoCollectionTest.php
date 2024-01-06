<?php

declare(strict_types=1);

namespace Test;

use Dto\Exception\SetValueException;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\PersonDto;
use Tests\Fixtures\PersonDtoCollection;
use Tests\Fixtures\ProductDto;

class DtoCollectionTest extends TestCase
{
    private readonly int $aliceAge;
    private readonly string $aliceName;
    private readonly PersonDto $aliceDto;

    /**
     * @throws SetValueException
     */
    public function setUp(): void
    {
        [$this->aliceName, $this->aliceAge] = ['Alice', 25];
        
        $this->aliceDto = new PersonDto(['name' => $this->aliceName, 'age' => $this->aliceAge]);
    }

    /**
     * @group ok
     * @throws SetValueException
     */
    public function testAddMethodSuccess(): void
    {
        [$bobName, $ageBob] = ['Bob', 30];
        [$samName, $samAge] = ['Sam', 35];

        $bobDto = new PersonDto(['name' => $bobName, 'age' => $ageBob]);
        $samDto = new PersonDto(['name' => $samName, 'age' => $samAge]);

        $dtoCollection = new PersonDtoCollection($this->aliceDto);
        $dtoCollection->add($bobDto);
        $dtoCollection->add($samDto);

        $this->assertCount(3, $dtoCollection);

        $this->assertEquals($this->aliceName, $dtoCollection->first()->getName());
        $this->assertEquals($this->aliceAge, $dtoCollection->first()->getAge());

        $this->assertEquals($this->aliceName, $dtoCollection->getItem(0)->getName());
        $this->assertEquals($this->aliceAge, $dtoCollection->getItem(0)->getAge());

        $this->assertEquals($bobName, $dtoCollection->getItem(1)->getName());
        $this->assertEquals($ageBob, $dtoCollection->getItem(1)->getAge());

        $this->assertEquals($samName, $dtoCollection->getItem(2)->getName());
        $this->assertEquals($samAge, $dtoCollection->getItem(2)->getAge());
    }

    /**
     * @group ok
     * @throws SetValueException
     */
    public function testAddMethodThrowsException(): void
    {
        [$price, $type, $available] = [999, 'ticket', true];

        $productDto = new ProductDto([
            'price' => $price,
            'type' => $type,
            'available' => $available,
        ]);

        $dtoCollection = new PersonDtoCollection();
        $dtoCollection->add($this->aliceDto);

        $this->assertCount(1, $dtoCollection);

        $msg = \sprintf(
            'DtoCollection: %s | Expected Dto: %s | Given Dto: %s',
            PersonDtoCollection::class,
            PersonDto::class,
            ProductDto::class,
        );

        $this->expectException(SetValueException::class);
        $this->expectExceptionMessage($msg);
        $dtoCollection->add($productDto);
    }
}