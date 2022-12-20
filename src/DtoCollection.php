<?php

declare(strict_types=1);

namespace Dto;

class DtoCollection extends Collection implements ArrayableInterface
{
    public function __construct(array $items = [], string $entityClass = DtoInterface::class)
    {
        if ($entityClass !== DtoInterface::class && !\is_subclass_of($entityClass, DtoInterface::class)) {
            throw new \InvalidArgumentException('Class must implement DtoInterface');
        }

        parent::__construct($entityClass, $items);
    }

    public function toArray(): array
    {
        return \array_map(static fn(ArrayableInterface $dto) => $dto->toArray(), $this->items);
    }
}
