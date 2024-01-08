<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\Dto;

/**
// * @method int getPrice()
 * @method ColorEnum getColor()
 */
class WithEnumDto extends Dto
{
    protected int $price;
    protected ColorEnum $color;
}
