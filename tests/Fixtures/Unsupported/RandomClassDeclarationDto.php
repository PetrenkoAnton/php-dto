<?php

declare(strict_types=1);

namespace Tests\Fixtures\Unsupported;

use Dto\Dto;

class RandomClassDeclarationDto extends Dto
{
    protected RandomClass $name;
}
