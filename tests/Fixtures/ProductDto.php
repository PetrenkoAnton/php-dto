<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\Dto;

/**
 * @method int getPrice()
 * @method string getType()
 * @method bool isAvailable()
 */
class ProductDto extends Dto
{
    protected int $price;
    protected string $type;
    protected bool $available;
}
