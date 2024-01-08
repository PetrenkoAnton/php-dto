<?php

declare(strict_types=1);

namespace Dto\Exception;

class SetValueEnumException extends \Exception
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
            \sprintf(
                "Dto: %s | Property: %s | Enum: %s | Expected values: %s | Given type: %s | Value: %s",
                $dto,
                $property,
                $type,
                $expectedValues,
                $givenType,
                \json_encode($value),
            )
        );
    }
}
