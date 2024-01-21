<?php

declare(strict_types=1);

namespace Dto;

/**
 * @psalm-suppress UnusedClass
 */
interface DtoFactoryInterface
{
    /**
     * @param array<string,mixed> $data
     */
    public function create(array $data): Dto;
}
