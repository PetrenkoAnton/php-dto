<?php

declare(strict_types=1);

namespace Dto;

interface DtoFactoryInterface
{
    public function create(array $data): Dto;
}