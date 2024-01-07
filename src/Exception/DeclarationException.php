<?php

declare(strict_types=1);

namespace Dto\Exception;

class DeclarationException extends \Exception
{
    public function __construct(string $dto, string $property, string $msg)
    {
        parent::__construct(
            \sprintf(
                "Dto: %s | Property: %s | Err: %s",
                $dto,
                $property,
                $msg
            )
        );
    }
}
