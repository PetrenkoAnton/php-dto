<?php

declare(strict_types=1);

namespace Dto\Exception\DeclarationExceptions;

use Dto\Exception\DeclarationException;

class ObjectDeclarationException extends DeclarationException
{
    public function __construct(string $dto, string $property)
    {
        parent::__construct($dto, $property, 'Unsupported object property type declaration');
    }
}
