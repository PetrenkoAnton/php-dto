<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\Dto;

/**
 * @method bool isPropertyBool()
 * @method string getPropertyStringTwo()
 */
class ExampleDto extends Dto
{
    protected bool $propertyBool;
    protected string $propertyStringOne;
    protected string $propertyStringTwo;

    public function getPropertyStringOne(): string
    {
        return \strtoupper($this->propertyStringOne);
    }
}
