<?php

declare(strict_types=1);

namespace Dto;

use Dto\Common\Collection;

abstract class DtoCollection extends Collection
{
    public function add(DtoInterface $dto): void
    {
        $this->items[] = $dto;
    }
}