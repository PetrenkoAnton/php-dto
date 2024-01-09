<?php

declare(strict_types=1);

namespace Dto\Exception\Internal;

final class EnumBackingValueException extends \Exception
{
    public function __construct(private readonly array $values)
    {
        parent::__construct();
    }

    public function getData(): array
    {
        return $this->values;
    }
}
