<?php

declare(strict_types=1);

namespace Dto\Exception\DtoException\InitDtoException;

use Dto\Exception\DtoException\InitDtoException;

use function sprintf;

class DtoCollectionConstructorException extends InitDtoException
{
    public function __construct(string $dtoCollection)
    {
        parent::__construct(
            message: sprintf('DtoCollection: %s | Err: Invalid constructor declaration', $dtoCollection),
            code: self::INVALID_DTO_COLLECTION_CONSTRUCTOR_CODE,
        );
    }
}
