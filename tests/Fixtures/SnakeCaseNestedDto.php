<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\Dto;

/**
 * @method ProductDto getProduct()
 * @method int getActualNumber()
 * @method string getProviderName()
 * @psalm-suppress PossiblyUnusedProperty
 * @psalm-suppress PropertyNotSetInConstructor
 */
class SnakeCaseNestedDto extends Dto
{
    protected SnakeCaseDto $snakeCase;
    protected int $actualNumber;
    protected string $providerName;
}
