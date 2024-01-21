<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\Dto;

/**
 * @method int getPrice()
 * @method string getType()
 * @method array getInfo()
 * @method bool isAvailable()
 * @psalm-suppress PossiblyUnusedProperty
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ProductDto extends Dto
{
    protected int $price;
    protected string $type;
    /**
     * @var array<mixed> $info
     */
    protected array $info;
    protected bool $available;
}
