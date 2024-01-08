<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\Dto;

/**
 * @method ColorEnum getColor()
 */
class WithNoBackedEnumDto extends Dto
{
    protected NoBackedColorEnum $color;
}
