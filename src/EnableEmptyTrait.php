<?php

declare(strict_types=1);

namespace Dto;

trait EnableEmptyTrait
{
    protected function enableEmpty(): bool
    {
        return true;
    }
}