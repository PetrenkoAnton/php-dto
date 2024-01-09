<?php

declare(strict_types=1);

namespace Dto\Exception\DtoException\SetupDtoException;

use Dto\Exception\DtoException\SetupDtoException;

final class AddDtoException extends SetupDtoException
{
    public function __construct(string $dtoCollection, string $expectedDto, string $givenDto)
    {
        parent::__construct(
            message: \sprintf(
                "DtoCollection: %s | Expected Dto: %s | Given Dto: %s",
                $dtoCollection,
                $expectedDto,
                $givenDto,
            ),
            code: self::ADD_DTO_EXCEPTION,
        );
    }
}
