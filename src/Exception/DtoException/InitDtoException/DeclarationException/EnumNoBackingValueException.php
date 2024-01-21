<?php

declare(strict_types=1);

namespace Dto\Exception\DtoException\InitDtoException\DeclarationException;

use Dto\Exception\DtoException\InitDtoException\DeclarationException;

final class EnumNoBackingValueException extends DeclarationException
{
    public function __construct(string $dto, string $property)
    {
        parent::__construct(
            dto: $dto,
            property: $property,
            message: 'No backing value for enum',
            code: self::ENUM_NO_BACKING_VALUE_CODE,
        );
    }
}
