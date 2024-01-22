<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\DtoCollection;

class ProductDtoCollection extends DtoCollection
{
    public function __construct(ProductDto ...$items)
    {
        parent::__construct(...$items);
    }
}
