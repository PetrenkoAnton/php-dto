<?php

declare(strict_types=1);

namespace Dto;

/**
 * @psalm-suppress UnusedClass
 */
interface DtoFactoryInterface
{
    public function create(array $data): Dto;
}