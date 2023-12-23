<?php

declare(strict_types=1);

namespace Dto\Common;

abstract class Collection implements CollectionInterface, \Countable
{
    protected array $items = [];

    abstract public function __construct();

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

    public function first(): mixed
    {
        $first = \reset($this->items);

        return false == $first ? null : $first;
    }

    public function count(): int
    {
        return \count($this->items);
    }
}