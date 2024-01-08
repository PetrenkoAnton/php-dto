<?php

declare(strict_types=1);

namespace Dto\Exception;

class GetValueException extends \Exception
{
    public function __construct(string $dto, string $msg)
    {
        parent::__construct(
            \sprintf(
                "Dto: %s | %s",
                $dto,
                $msg,
            )
        );
    }
}
