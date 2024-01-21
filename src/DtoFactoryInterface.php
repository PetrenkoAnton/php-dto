<?php

declare(strict_types=1);

namespace Dto;

/**
 * @psalm-suppress UnusedClass
 */
interface DtoFactoryInterface
{
    /**
     * @throws DtoException
     */
    public function create(array $data): Dto;
}
