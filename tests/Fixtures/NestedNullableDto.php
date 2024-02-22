<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\Dto;

/**
 * @method ?sting getType()
 * @method ?ErrorDto getError()
 * @psalm-suppress PossiblyUnusedProperty
 * @psalm-suppress PropertyNotSetInConstructor
 */
class NestedNullableDto extends Dto
{
    protected string $type;
    protected ?ErrorDto $error;
}
