<?php

declare(strict_types=1);

namespace Dto\Exception\DtoException;

use Dto\Exception\DtoException;

class HandleDtoException extends DtoException
{
    public function __construct(string $message, int $code)
    {
        parent::__construct(
            message: $message,
            code: $code,
        );
    }
}
