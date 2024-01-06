<?php

declare(strict_types=1);

namespace Test;

use Dto\Exception\GetValueException;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\FirstSimpleDataDto;

class DtoTest extends TestCase
{
    private readonly bool $bool;
    private readonly string $stringOne;
    private readonly string $stringTwo;
    private readonly array $arrayOne;
    private readonly array $arrayTwo;
    private readonly FirstSimpleDataDto $dto;

    public function setUp(): void
    {
        $this->bool = true;
        $this->stringOne = 'StringOne';
        $this->stringTwo = 'StringTwo';
        $this->arrayOne = ['key_1' => 'value_1', 'key_2' => 'value_2'];
        $this->arrayTwo = ['key_3' => 'value_3', 'key_4' => 'value_4'];

        $data = [
            'bool' => $this->bool,
            'stringOne' => $this->stringOne,
            'stringTwo' => $this->stringTwo,
            'arrayOne' => $this->arrayOne,
            'arrayTwo' => $this->arrayTwo,
        ];

        $this->dto = new FirstSimpleDataDto($data);

    }

    /**
     * @test
     * @group ok
     */
    public function testSuccess(): void
    {
        $this->assertEquals($this->bool, $this->dto->isBool());
        $this->assertEquals($this->stringOne, $this->dto->getStringOne());
        $this->assertEquals($this->stringTwo, $this->dto->getStringTwo());
        $this->assertEquals($this->arrayOne, $this->dto->getArrayOne());
        $this->assertEquals($this->arrayTwo, $this->dto->getArrayTwo());
    }

    /**
     * @test
     * @group ok
     */
    public function testInvalidGetterThrowsException(): void
    {
        $this->expectException(GetValueException::class);
        $this->dto->getStringThree();

        $this->expectException(GetValueException::class);
        $this->dto->getStringFour();

        $this->expectException(GetValueException::class);
        $this->dto->getArrayThree();

        $this->expectException(GetValueException::class);
        $this->dto->getArrayFour();
    }
}
