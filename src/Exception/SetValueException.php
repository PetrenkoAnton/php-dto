<?php

declare(strict_types=1);

namespace Dto\Exception;

class SetValueException extends \Exception
{
    public function __construct(string $dto, string $property, string $value)
    {
        parent::__construct(
            \sprintf(
                "Dto: %s | Property: %s | Value: %s",
                $dto,
                $property,
                $value,
            )
        );
    }
}
