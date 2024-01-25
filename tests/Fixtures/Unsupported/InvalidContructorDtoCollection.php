<?php

declare(strict_types=1);

namespace Tests\Fixtures\Unsupported;

use Dto\DtoCollection;

class InvalidContructorDtoCollection extends DtoCollection
{
    /**
     * @psalm-suppress UnusedProperty
     */
    private int $id;

    // Invalid constructor declaration
    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
