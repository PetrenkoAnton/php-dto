<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\Dto;
use Tests\Fixtures\NestedDto\SimpleNestedDataDto;

/**
 * @method string getString()
 * @method SimpleNestedDataDto getSimpleNestedData()
 */
class SimpleNestedDto extends Dto
{
    protected string $string;
    protected SimpleNestedDataDto $simpleNestedData;
}
