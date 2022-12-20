<?php

declare(strict_types=1);

namespace Dto\Exception;

class SetValueException extends \Exception
{
    public function __construct(string $dtoClass, string $property, mixed $value, \Throwable $previous)
    {
        parent::__construct(
            \sprintf(
                'Set value error. DTO: %s, property: %s, value: %s. Message: %s',
                $dtoClass,
                $property,
                \print_r($value, true),
                $previous->getMessage()
            ),
            $previous->getCode(),
            $previous
        );
    }
}
