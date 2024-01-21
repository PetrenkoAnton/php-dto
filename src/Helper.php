<?php

declare(strict_types=1);

namespace Dto;

use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;

class Helper
{
    /**
     * @throws ReflectionException
     *
     * @psalm-suppress ArgumentTypeCoercion, PossiblyUndefinedIntArrayOffset, PossiblyNullReference
     */
    public static function getConstructorFirstParameterClassName(string | object $class): string
    {
        $type = (new ReflectionClass($class))->getConstructor()->getParameters()[0]->getType();

        /** @var ReflectionNamedType $type */
        return $type->getName();
    }
}
