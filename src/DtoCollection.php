<?php

declare(strict_types=1);

namespace Dto;

use Dto\Common\ArrayableInterface;
use Dto\Common\Collection;
use Dto\Exception\AddDtoCollectionException;
use ReflectionClass;

abstract class DtoCollection extends Collection
{
    protected array $items = [];

    public function __construct(DtoInterface ...$items)
    {
        $this->items = $items;
    }

    /**
     * @throws AddDtoCollectionException
     */
    public function add(DtoInterface $dto): void
    {
        $this->validate($dto);

        $this->items[] = $dto;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return \array_map(static function (ArrayableInterface $dto) {
            return $dto->toArray();
        }, $this->items);
    }

    /**
     * @throws AddDtoCollectionException
     */
    private function validate(DtoInterface $dto): void
    {
        $expectedDto = (new ReflectionClass($this))->getConstructor()->getParameters()[0]->getType()->getName();

        if ($expectedDto !== $dto::class)
            throw new AddDtoCollectionException($this::class, $expectedDto, $dto::class);
    }
}