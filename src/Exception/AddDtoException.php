<?php

declare(strict_types=1);

namespace Dto\Exception;

class AddDtoException extends \Exception
{
    public function __construct(string $dtoCollection, string $expectedDto, string $givenDto)
    {
        parent::__construct(
            \sprintf(
                "DtoCollection: %s | Expected Dto: %s | Given Dto: %s",
                $dtoCollection,
                $expectedDto,
                $givenDto,
            )
        );
    }
}
