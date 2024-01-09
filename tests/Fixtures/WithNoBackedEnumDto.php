<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\Dto;
use Tests\Fixtures\Enum\ColorEnum;
use Tests\Fixtures\Enum\NoBackedColorEnum;

/**
 * @method ColorEnum getColor()
 */
class WithNoBackedEnumDto extends Dto
{
    protected NoBackedColorEnum $color;
}
