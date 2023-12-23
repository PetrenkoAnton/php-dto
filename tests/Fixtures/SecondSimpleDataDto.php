<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\Dto;

/**
 * @method string getStringParam()
 */
class SecondSimpleDataDto extends Dto
{
    protected string $stringParam;
}
