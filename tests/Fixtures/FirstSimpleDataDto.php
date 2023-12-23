<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\Dto;

/**
 * @method bool isBool()
 * @method string getStringOne()
 * @method string getStringTwo()
 * @method array getArrayOne()
 * @method array getArrayTwo()
 */
class FirstSimpleDataDto extends Dto
{
    protected bool $bool;
    protected string $stringOne;
    protected string $stringTwo;
    protected array $arrayOne;
    protected array $arrayTwo;
}
