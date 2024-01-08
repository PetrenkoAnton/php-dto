<?php

declare(strict_types=1);

namespace Test;

use Dto\Exception\DtoException;
use Dto\Exception\DtoException\SetupDtoException;
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
     * @throws DtoException
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
     * @throws DtoException
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
     * @throws DtoException
     */
    public function testAddMethodThrowsSetupDtoException(): void
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

        $this->expectException(SetupDtoException::class);
        $this->expectExceptionMessage('DtoCollection: Tests\Fixtures\PersonDtoCollection | Expected Dto: Tests\Fixtures\PersonDto | Given Dto: Tests\Fixtures\ProductDto');
        $this->expectExceptionCode(201);
        $dtoCollection->add($productDto);
    }
}
