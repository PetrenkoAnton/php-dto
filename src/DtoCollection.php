<?php

declare(strict_types=1);

namespace Dto;

use Dto\Common\Collection;
use Dto\Exception\SetValueException;
use ReflectionClass;

abstract class DtoCollection extends Collection
{
    protected array $items = [];

    public function __construct(DtoInterface ...$items)
    {
        $this->items = $items;
    }

    /**
     * @throws SetValueException
     */
    public function add(DtoInterface $dto): void
    {
        $this->validate($dto);

        $this->items[] = $dto;
    }

    /**
     * @throws SetValueException
     */
    private function validate(DtoInterface $dto): void
    {
        $expectedDto = (new ReflectionClass($this))->getConstructor()->getParameters()[0]->getType()->getName();

        if ($expectedDto !== $dto::class)
            throw new SetValueException($this::class, $expectedDto, $dto::class);
    }
}