<?php

declare(strict_types=1);

namespace Test;

use Collection\Exception\CollectionException;
use Collection\Exception\CollectionException\InvalidKeyCollectionException;
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

    private readonly int $bobAge;
    private readonly string $bobName;

    private readonly int $samAge;
    private readonly string $samName;

    private readonly PersonDtoCollection $dtoCollection;

    /**
     * @throws DtoException
     * @throws CollectionException
     */
    public function setUp(): void
    {
        [$this->aliceName, $this->aliceAge] = ['Alice', 25];

        $aliceData = [
            'name' => $this->aliceName,
            'age' => $this->aliceAge,
        ];
        
        $this->aliceDto = new PersonDto($aliceData);

        [$this->bobName, $this->bobAge] = ['Bob', 30];

        $bobData = [
            'name' => $this->bobName,
            'age' => $this->bobAge,
        ];

        [$this->samName, $this->samAge] = ['Sam', 35];

        $samData = [
            'name' => $this->samName,
            'age' => $this->samAge,
        ];

        $bobDto = new PersonDto($bobData);
        $samDto = new PersonDto($samData);

        $this->dtoCollection = new PersonDtoCollection($this->aliceDto);
        $this->dtoCollection->add($bobDto);
        $this->dtoCollection->add($samDto);
    }

    /**
     * @group ok
     * @throws DtoException
     * @throws CollectionException
     */
    public function testAddMethodSuccess(): void
    {
        $this->assertCount(3, $this->dtoCollection);

        $this->assertEquals($this->aliceName, $this->dtoCollection->first()->getName());
        $this->assertEquals($this->aliceAge, $this->dtoCollection->first()->getAge());

        $this->assertEquals($this->aliceName, $this->dtoCollection->getItem(0)->getName());
        $this->assertEquals($this->aliceAge, $this->dtoCollection->getItem(0)->getAge());

        $this->assertEquals($this->bobName, $this->dtoCollection->getItem(1)->getName());
        $this->assertEquals($this->bobAge, $this->dtoCollection->getItem(1)->getAge());

        $this->assertEquals($this->samName, $this->dtoCollection->getItem(2)->getName());
        $this->assertEquals($this->samAge, $this->dtoCollection->getItem(2)->getAge());
    }

    /**
     * @group ok
     * @throws CollectionException
     * @dataProvider dpInvalidKeys
     */
    public function testGetMethodThrowsInvalidKeyCollectionException(int $key): void
    {
        $this->expectException(InvalidKeyCollectionException::class);
        $this->expectExceptionMessage("Collection: Tests\Fixtures\PersonDtoCollection | Invalid key: $key");
        $this->expectExceptionCode(200);
        $this->dtoCollection->getItem($key);
    }

    public function dpInvalidKeys(): array
    {
        return [
            [-100],
            [-1],
            [3],
            [4],
            [5],
            [100],
        ];
    }

    /**
     * @group ok
     * @throws DtoException
     * @throws CollectionException
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
