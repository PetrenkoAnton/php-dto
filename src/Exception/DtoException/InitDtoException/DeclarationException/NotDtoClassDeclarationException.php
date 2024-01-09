<?php

declare(strict_types=1);

namespace Dto\Exception\DtoException\InitDtoException\DeclarationException;

use Dto\Exception\DtoException\InitDtoException\DeclarationException;

final class NotDtoClassDeclarationException extends DeclarationException
{
    public function __construct(string $dto, string $property)
    {
        parent::__construct(
            dto: $dto,
            property: $property,
            message: 'Class must implement DtoInterface',
            code: self::NOT_DTO_CLASS_DECLARATION_CODE,
        );
    }
}
