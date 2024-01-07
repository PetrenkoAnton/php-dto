<?php

declare(strict_types=1);

namespace Tests\Fixtures\Unsupported;

use Dto\Dto;

class NullableDeclarationDto extends Dto
{
    protected ?string $name;
}
