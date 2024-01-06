<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\DtoCollection;

class PersonDtoCollection extends DtoCollection
{
    public function __construct(PersonDto ...$items)
    {
        $this->items = $items;
        parent::__construct();
    }
}
