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
    private int $aliceAge;
    private string $aliceName;
    private PersonDto $aliceDto;

    private int $bobAge;
    private string $bobName;

    private int $samAge;
    private string $samName;

    private PersonDtoCollection $dtoCollection;

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
     * @throws DtoException
     * @throws CollectionException
     *
     * @group ok
     */
    public function testAddMethodSuccess(): void
    {
        $this->assertCount(3, $this->dtoCollection);

        $personDto = $this->dtoCollection->first();
        /** @var PersonDto $personDto */

        $this->assertEquals($this->aliceName, $personDto->getName());
        $this->assertEquals($this->aliceAge, $personDto->getAge());

        $personDto = $this->dtoCollection->getItem(0);
        /** @var PersonDto $personDto */

        $this->assertEquals($this->aliceName, $personDto->getName());
        $this->assertEquals($this->aliceAge, $personDto->getAge());

        $personDto = $this->dtoCollection->getItem(1);
        /** @var PersonDto $personDto */

        $this->assertEquals($this->bobName, $personDto->getName());
        $this->assertEquals($this->bobAge, $personDto->getAge());

        $personDto = $this->dtoCollection->getItem(2);
        /** @var PersonDto $personDto */

        $this->assertEquals($this->samName, $personDto->getName());
        $this->assertEquals($this->samAge, $personDto->getAge());
    }

    /**
     * @throws CollectionException
     *
     * @group ok
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
     * @throws DtoException
     * @throws CollectionException
     *
     * @group ok
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
        // phpcs:ignore
        $this->expectExceptionMessage('DtoCollection: Tests\Fixtures\PersonDtoCollection | Expected Dto: Tests\Fixtures\PersonDto | Given Dto: Tests\Fixtures\ProductDto');
        $this->expectExceptionCode(201);
        $dtoCollection->add($productDto);
    }
}
