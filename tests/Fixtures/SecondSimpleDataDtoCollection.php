<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\DtoCollection;
use Dto\DtoInterface;

/**
 * @method string getStringParam()
 */
class SecondSimpleDataDtoCollection extends DtoCollection
{
    public function __construct(SecondSimpleDataDto ...$items)
    {
        $this->items = $items;
    }

    /**
     * @param SecondSimpleDataDto $dto
     */
    public function add(DtoInterface $dto): void
    {
        $this->items[] = $dto;
    }
}
