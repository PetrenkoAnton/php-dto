<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\Dto;

/**
 * @method string getName()
 * @method int getAge()
 */
class PersonDto extends Dto
{
    protected string $name;
    protected int $age;
}
