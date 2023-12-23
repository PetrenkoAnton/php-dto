<?php

declare(strict_types=1);

namespace Dto\Exception;

class GetValueException extends \Exception
{
    public function __construct(string $dtoClass, string $method, \Throwable $previous)
    {
        parent::__construct(
            \sprintf(
                'Get value error. DTO: %s, method: %s. Message: %s',
                $dtoClass,
                $method,
                $previous->getMessage()
            ),
            $previous->getCode(),
            $previous
        );
    }
}
