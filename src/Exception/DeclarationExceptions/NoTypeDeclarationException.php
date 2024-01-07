<?php

declare(strict_types=1);

namespace Dto\Exception\DeclarationExceptions;

use Dto\Exception\DeclarationException;

class NoTypeDeclarationException extends DeclarationException
{
    public function __construct(string $dto, string $property)
    {
        parent::__construct($dto, $property, 'Mission property type declaration');
    }
}
