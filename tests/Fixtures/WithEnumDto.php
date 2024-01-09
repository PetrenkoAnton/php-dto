<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\Dto;
use Tests\Fixtures\Enum\ColorEnum;

/**
 * @method ColorEnum getColor()
 */
class WithEnumDto extends Dto
{
    protected ColorEnum $color;
}
