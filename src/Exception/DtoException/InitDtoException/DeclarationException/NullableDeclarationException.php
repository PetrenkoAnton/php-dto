<?php

declare(strict_types=1);

namespace Dto\Exception\DtoException\InitDtoException\DeclarationException;

use Dto\Exception\DtoException\InitDtoException\DeclarationException;

final class NullableDeclarationException extends DeclarationException
{
    public function __construct(string $dto, string $property)
    {
        parent::__construct(
            dto: $dto,
            property: $property,
            message: 'Unsupported nullable property type declaration',
            code: self::NULLABLE_DECLARATION_CODE,
        );
    }
}
