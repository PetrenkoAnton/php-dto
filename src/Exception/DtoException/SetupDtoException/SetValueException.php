<?php

declare(strict_types=1);

namespace Dto\Exception\DtoException\SetupDtoException;

use Dto\Exception\DtoException\SetupDtoException;

final class SetValueException extends SetupDtoException
{
    public function __construct(string $dto, string $property, string $expectedType, string $givenType, mixed $value)
    {
        parent::__construct(
            message: \sprintf(
                "Dto: %s | Property: %s | Expected type: %s | Given type: %s | Value: %s",
                $dto,
                $property,
                $expectedType,
                $givenType,
                \json_encode($value),
            ),
            code: self::SET_INVALID_VALUE_CODE,
        );
    }
}
