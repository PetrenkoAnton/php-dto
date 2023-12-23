<?php

declare(strict_types=1);

namespace Tests\Fixtures\NestedDto;

use Dto\Dto;

/**
 * @method bool isBool()
 * @method string getString()
 */
class SimpleNestedDataDto extends Dto
{
    protected bool $bool;
    protected string $string;
}
