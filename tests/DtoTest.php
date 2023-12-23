<?php

declare(strict_types=1);

namespace Test;

use Dto\Exception\GetValueException;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\ExampleDto;

class DtoTest extends TestCase
{
    public function testDto(): void
    {
        $propertyBool = true;
        $propertyString1 = 'string 1';
        $propertyString2 = 'string 2';

        $data = [
            'propertyBool' => $propertyBool,
            'propertyStringOne' => $propertyString1,
            'propertyStringTwo' => $propertyString2,
        ];

        $exampleDto = new ExampleDto($data);

        $this->assertEquals($propertyBool, $exampleDto->isPropertyBool());
        $this->assertEquals($propertyString1, $exampleDto->getPropertyStringOne());
        $this->assertEquals($propertyString2, $exampleDto->getPropertyStringTwo());

        $this->expectException(GetValueException::class);
        $exampleDto->getPropertyStringThree();
    }
}
