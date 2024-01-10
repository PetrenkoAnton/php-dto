<?php

declare(strict_types=1);

namespace Dto;

use Countable;
use ReflectionClass;

abstract class Collection implements Countable, Collectable
{
    protected array $items = [];

    public function __construct(Collectable ...$items)
    {
        $this->items = $items;
    }

    /**
     * @throws CollectionException
     */
    public function add(Collectable $item)
    {
        $this->validate($item);

        $this->items[] = $item;
    }

    /**
     * @throws InvalidItemTypeCollectionException
     */
    private function validate(Collectable $item): void
    {
        $expectedClass = (new ReflectionClass($this))->getConstructor()->getParameters()[0]->getType()->getName();

        if ($expectedClass !== $item::class)
            throw new InvalidItemTypeCollectionException($this::class, $expectedClass, $item::class);
    }

    public function filter(callable $callback): Collection
    {
        $copy = clone $this;
        $copy->items = \array_filter($this->items, $callback);

        return $copy;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @throws InvalidKeyCollectionException
     */
    public function getItem(int $key): Collectable
    {
        return $this->items[$key] ?? throw new InvalidKeyCollectionException($this::class, $key);
    }

    public function first(): mixed
    {
        $first = \reset($this->items);

        return false == $first ? null : $first;
    }

    public function count(): int
    {
        return \count($this->items);
    }

    public function toArray(): array
    {
        return \array_map(static function (Arrayable $item) {
            return $item->toArray();
        }, $this->items);
    }
}