<?php

declare(strict_types=1);

namespace Test;

use Dto\Exception\DtoException\InitDtoException\DeclarationException;
use Dto\Exception\DtoException\SetupDtoException\AddDtoException;
use Dto\Exception\DtoException\SetupDtoException\InputDataException;
use Dto\Exception\DtoException\SetupDtoException\SetValueException;
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
     * @throws DeclarationException
     * @throws InputDataException
     * @throws SetValueException
     */
    public function setUp(): void
    {
        [$this->aliceName, $this->aliceAge] = ['Alice', 25];

        $aliceData = [
            'name' => $this->aliceName,
            'age' => $this->aliceAge,
        ];
        
        $this->aliceDto = new PersonDto($aliceData);
    }

    /**
     * @group ok
     * @throws DeclarationException
     * @throws InputDataException
     * @throws AddDtoException
     * @throws SetValueException
     */
    public function testAddMethodSuccess(): void
    {
        $bobData = [
            'name' => $bobName = 'Bob',
            'age' => $bobAge = 30,
        ];

        $samData = [
            'name' => $samName = 'Sam',
            'age' => $samAge = 35,
        ];

        $bobDto = new PersonDto($bobData);
        $samDto = new PersonDto($samData);

        $dtoCollection = new PersonDtoCollection($this->aliceDto);
        $dtoCollection->add($bobDto);
        $dtoCollection->add($samDto);

        $this->assertCount(3, $dtoCollection);

        $this->assertEquals($this->aliceName, $dtoCollection->first()->getName());
        $this->assertEquals($this->aliceAge, $dtoCollection->first()->getAge());

        $this->assertEquals($this->aliceName, $dtoCollection->getItem(0)->getName());
        $this->assertEquals($this->aliceAge, $dtoCollection->getItem(0)->getAge());

        $this->assertEquals($bobName, $dtoCollection->getItem(1)->getName());
        $this->assertEquals($bobAge, $dtoCollection->getItem(1)->getAge());

        $this->assertEquals($samName, $dtoCollection->getItem(2)->getName());
        $this->assertEquals($samAge, $dtoCollection->getItem(2)->getAge());
    }

    /**
     * @group ok
     * @throws DeclarationException
     * @throws InputDataException
     * @throws AddDtoException
     * @throws SetValueException
     */
    public function testAddMethodThrowsException(): void
    {
        $data = [
            'price' => 999,
            'type' => 'ticket',
            'info' => ['key' => 'value'],
            'available' => true,
        ];

        $productDto = new ProductDto($data);

        $dtoCollection = new PersonDtoCollection();
        $dtoCollection->add($this->aliceDto);

        $this->assertCount(1, $dtoCollection);

        $msg = \sprintf(
            'DtoCollection: %s | Expected Dto: %s | Given Dto: %s',
            PersonDtoCollection::class,
            PersonDto::class,
            ProductDto::class,
        );

        $this->expectException(AddDtoException::class);
        $this->expectExceptionMessage($msg);
        $dtoCollection->add($productDto);
    }
}
