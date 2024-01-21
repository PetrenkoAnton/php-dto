<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\Dto;
use Tests\Fixtures\Enum\ColorEnum;
use Tests\Fixtures\Enum\NoBackedColorEnum;

/**
 * @method ColorEnum getColor()
 * @psalm-suppress PossiblyUnusedProperty
 * @psalm-suppress PropertyNotSetInConstructor
 */
class WithNoBackedEnumDto extends Dto
{
    protected NoBackedColorEnum $color;
}
