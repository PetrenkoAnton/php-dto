<?php

namespace Dto;

use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;

class Helper
{
    /**
     * @psalm-suppress ArgumentTypeCoercion, PossiblyUndefinedIntArrayOffset, PossiblyNullReference
     *
     * @throws ReflectionException
     */
    public static function getConstructorFirstParameterClassName(string|object $class): string
    {
        $type = (new ReflectionClass($class))->getConstructor()->getParameters()[0]->getType();
        /** @var ReflectionNamedType $type */
        return $type->getName();
    }
}