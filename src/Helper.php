<?php

declare(strict_types=1);

namespace Dto;

use Dto\Exception\DtoException\InitDtoException\DtoCollectionConstructorException;
use ReflectionClass;
use ReflectionNamedType;

class Helper
{
    public static function getConstructorFirstParameterClassName(object $class): string
    {
        /** @psalm-suppress PossiblyUndefinedIntArrayOffset */
        $type = (new ReflectionClass($class))->getConstructor()?->getParameters()[0]?->getType();

        if (!$type) {
            throw new DtoCollectionConstructorException($class::class);
        }

        /** @var ReflectionNamedType $type */

        return $type->getName();
    }
}
