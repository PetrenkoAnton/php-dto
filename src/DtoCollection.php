<?php

declare(strict_types=1);

namespace Dto;

use Dto\Exception\DtoException;
use Dto\Exception\DtoException\SetupDtoException\AddDtoException;
use ReflectionClass;

abstract class DtoCollection extends Collection
{
    protected array $items = [];

    public function __construct(Dto ...$items)
    {
        $this->items = $items;
    }

    /**
     * @throws DtoException
     */
    public function add(Dto $dto): void
    {
        $this->validate($dto);

        $this->items[] = $dto;
    }

    public function toArray(): array
    {
        return \array_map(static function (Arrayable $dto) {
            return $dto->toArray();
        }, $this->items);
    }

    /**
     * @throws AddDtoException
     */
    private function validate(Dto $dto): void
    {
        $expectedDto = (new ReflectionClass($this))->getConstructor()->getParameters()[0]->getType()->getName();

        if ($expectedDto !== $dto::class)
            throw new AddDtoException($this::class, $expectedDto, $dto::class);
    }
}