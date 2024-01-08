<?php

declare(strict_types=1);

namespace Dto\Exception\DtoException\InitDtoException;

use Dto\Exception\DtoException\InitDtoException;

class DeclarationException extends InitDtoException
{
    public function __construct(string $dto, string $property, string $message, int $code)
    {
        parent::__construct(
            message: \sprintf('Dto: %s | Property: %s | Err: %s', $dto, $property, $message),
            code: $code,
        );
    }
}
