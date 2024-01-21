<?php

declare(strict_types=1);

namespace Tests\Fixtures\Unsupported;

use Dto\Dto;

/**
 * @psalm-suppress PossiblyUnusedProperty
 * @psalm-suppress PropertyNotSetInConstructor
 */
class MixedDeclarationDto extends Dto
{
    protected mixed $name;
}
