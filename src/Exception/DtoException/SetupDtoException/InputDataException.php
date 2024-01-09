<?php

declare(strict_types=1);

namespace Dto\Exception\DtoException\SetupDtoException;

use Dto\Exception\DtoException\SetupDtoException;

final class InputDataException extends SetupDtoException
{
    public function __construct(string $dto, string $property)
    {
        parent::__construct(
            message: \sprintf(
                "Dto: %s | Property: %s | Err: No data",
                $dto,
                $property
            ),
            code: self::EMPTY_INPUT_DATA_CODE,
        );
    }
}
