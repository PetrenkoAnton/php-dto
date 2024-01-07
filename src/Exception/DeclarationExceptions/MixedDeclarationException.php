<?php

declare(strict_types=1);

namespace Dto\Exception\DeclarationExceptions;

use Dto\Exception\DeclarationException;

class MixedDeclarationException extends DeclarationException
{
    public function __construct(string $dto, string $property)
    {
        parent::__construct($dto, $property, 'Unsupported mixed property type declaration');
    }
}
