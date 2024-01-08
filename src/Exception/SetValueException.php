<?php

declare(strict_types=1);

namespace Dto\Exception;

class SetValueException extends \Exception
{
    public function __construct(string $dto, string $property, string $expectedType, string $givenType, mixed $value)
    {
        parent::__construct(
            \sprintf(
                "Dto: %s | Property: %s | Expected type: %s | Given type: %s | Value: %s",
                $dto,
                $property,
                $expectedType,
                $givenType,
                \json_encode($value),
            )
        );
    }
}
