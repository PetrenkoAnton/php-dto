<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\Dto;

/**
 * @method int getAge()
 * @method string getName()
 */
class PersonDto extends Dto
{
    protected int $age;
    protected string $name;
}
