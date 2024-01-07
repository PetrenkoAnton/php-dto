<?php

declare(strict_types=1);

namespace Dto\Exception;

class InputDataException extends \Exception
{
    public function __construct(string $dto, string $property)
    {
        parent::__construct(
            \sprintf(
                "Dto: %s | Property: %s | Err: No data",
                $dto,
                $property
            )
        );
    }
}
