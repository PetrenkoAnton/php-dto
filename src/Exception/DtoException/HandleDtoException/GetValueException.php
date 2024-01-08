<?php

declare(strict_types=1);

namespace Dto\Exception\DtoException\HandleDtoException;

use Dto\Exception\DtoException\HandleDtoException;

class GetValueException extends HandleDtoException
{
    public function __construct(string $dto, string $msg)
    {
        parent::__construct(
            message: \sprintf(
                "Dto: %s | %s",
                $dto,
                $msg,
            ),
            code: self::GET_VALUE_EXCEPTION_CODE,
        );
    }
}
