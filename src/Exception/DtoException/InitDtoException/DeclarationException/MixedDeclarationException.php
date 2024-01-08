<?php

declare(strict_types=1);

namespace Dto\Exception\DtoException\InitDtoException\DeclarationException;

use Dto\Exception\DtoException\InitDtoException\DeclarationException;

class MixedDeclarationException extends DeclarationException
{
    public function __construct(string $dto, string $property)
    {
        parent::__construct(
            dto: $dto,
            property: $property,
            message: 'Unsupported mixed property type declaration',
            code: self::MIXED_DECLARATION_CODE,
        );
    }
}
