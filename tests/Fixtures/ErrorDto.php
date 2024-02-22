<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\Dto;

/**
 * @method string getName()
 * @method int getAge()
 * @psalm-suppress PossiblyUnusedProperty
 * @psalm-suppress PropertyNotSetInConstructor
*/
class ErrorDto extends Dto
{
    protected string $message;
    protected int $code;
}
