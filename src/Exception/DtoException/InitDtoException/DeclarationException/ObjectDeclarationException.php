<?php

declare(strict_types=1);

namespace Dto\Exception\DtoException\InitDtoException\DeclarationException;

use Dto\Exception\DtoException\InitDtoException\DeclarationException;

final class ObjectDeclarationException extends DeclarationException
{
    public function __construct(string $dto, string $property)
    {
        parent::__construct(
            dto: $dto,
            property: $property,
            message: 'Unsupported object property type declaration',
            code: self::OBJECT_DECLARATION_CODE
        );
    }
}
