<?php

declare(strict_types=1);

namespace Dto;

use Dto\Exception\DtoException\SetupDtoException\AddDtoException;
use ReflectionClass;

abstract class DtoCollection extends Collection
{
    public function __construct(Dto ...$items)
    {
        parent::__construct(... $items);
    }

    /**
     * @throws CollectionException
     * @throws AddDtoException
     */
    public function add(Collectable $item)
    {
        try {
            parent::add($item);
        } catch (InvalidItemTypeCollectionException) {
            $expectedDto = (new ReflectionClass($this))->getConstructor()->getParameters()[0]->getType()->getName();

            throw new AddDtoException($this::class, $expectedDto, $item::class);
        }
    }
}