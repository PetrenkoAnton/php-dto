<?php

declare(strict_types=1);

namespace Dto\Exception\DtoException\SetupDtoException;

use Dto\Exception\DtoException\SetupDtoException;

class SetValueEnumException extends SetupDtoException
{
    public function __construct(
        string $dto,
        string $property,
        string $type,
        string $expectedValues,
        string $givenType,
        mixed $value
    )
    {
        parent::__construct(
            message: \sprintf(
                "Dto: %s | Property: %s | Enum: %s | Expected values: %s | Given type: %s | Value: %s",
                $dto,
                $property,
                $type,
                $expectedValues,
                $givenType,
                \json_encode($value),
            ),
            code: self::SET_INVALID_ENUM_VALUE_CODE,
        );
    }
}
