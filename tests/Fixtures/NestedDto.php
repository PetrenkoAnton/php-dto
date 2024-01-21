<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\Dto;

/**
 * @method PersonDtoCollection getPersons()
 * @method ProductDto getProduct()
 * @psalm-suppress PossiblyUnusedProperty
 * @psalm-suppress PropertyNotSetInConstructor
 */
class NestedDto extends Dto
{
    protected PersonDtoCollection $persons;
    protected ProductDto $product;
}
