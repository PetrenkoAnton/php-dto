<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\Dto;

/**
 * @method ColorEnum getColor()
 */
class WithEnumDto extends Dto
{
    protected ColorEnum $color;
}
