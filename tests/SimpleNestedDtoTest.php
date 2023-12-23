<?php

declare(strict_types=1);

namespace Test;

use PHPUnit\Framework\TestCase;
use Tests\Fixtures\SimpleNestedDto;
use Tests\Fixtures\NestedDto\SimpleNestedDataDto;

class SimpleNestedDtoTest extends TestCase
{
    private readonly bool $bool;
    private readonly string $stringOne;
    private readonly string $stringTwo;
    private readonly array $simpleNestedData;

    public function setUp(): void
    {
        $this->bool = true;
        $this->stringOne = 'StringOne';
        $this->stringTwo = 'StringTwo';

        $this->simpleNestedData = [
            'bool' => $this->bool,
            'string' => $this->stringTwo,
        ];

        $data = [
            'string' => $this->stringOne,
            'simpleNestedData' => $this->simpleNestedData,
        ];

        $this->dto = new SimpleNestedDto($data);

    }

    /**
     * @test
     * @group +
     */
    public function testSuccess(): void
    {
        $this->assertEquals($this->stringOne, $this->dto->getString());
        $this->assertEquals(SimpleNestedDataDto::class, get_class($this->dto->getSimpleNestedData()));
        $this->assertEquals($this->bool, $this->dto->getSimpleNestedData()->isBool());
        $this->assertEquals($this->stringTwo, $this->dto->getSimpleNestedData()->getString());
    }

//    /**
//     * @test
//     * @group ok
//     */
//    public function testInvalidGetterThrowsException(): void
//    {
//        $this->expectException(GetValueException::class);
//        $this->dto->getStringThree();
//
//        $this->expectException(GetValueException::class);
//        $this->dto->getStringFour();
//
//        $this->expectException(GetValueException::class);
//        $this->dto->getArrayThree();
//
//        $this->expectException(GetValueException::class);
//        $this->dto->getArrayFour();
//    }
}
