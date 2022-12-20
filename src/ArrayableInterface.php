<?php

declare(strict_types=1);

namespace Domain\Common\Dto;

interface ArrayableInterface
{
    public function toArray(): array;
}
