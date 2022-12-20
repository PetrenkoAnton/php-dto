<?php

declare(strict_types=1);

namespace Test;

use PHPUnit\Framework\TestCase;
use Tests\Fixtures\ExampleDto;

class DtoTest extends TestCase
{
    public function testDto(): void
    {
        $propertyBool = true;
        $propertyString = 'string';

        $data = [
            'propertyBool' => $propertyBool,
            'propertyString' => $propertyString,
        ];

        $exampleDto = new ExampleDto($data);

//        $this->assertEquals($propertyBool, $exampleDto->isPropertyBool());
        $this->assertEquals($propertyString, $exampleDto->getPropertyString());
    }
}
