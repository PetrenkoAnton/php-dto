<?php

declare(strict_types=1);

namespace Dto;

use Collection\Collectable;
use Collection\Collection;
use Collection\Exception\CollectionException;
use Collection\Exception\CollectionException\InvalidItemTypeCollectionException;
use Dto\Exception\DtoException\SetupDtoException\AddDtoException;
use ReflectionException;

abstract class DtoCollection extends Collection
{
    public function __construct(Dto ...$items)
    {
        parent::__construct(...$items);
    }

    /**
     * @throws CollectionException
     * @throws AddDtoException
     * @throws ReflectionException
     */
    public function add(Collectable $item): void
    {
        try {
            parent::add($item);
        } catch (InvalidItemTypeCollectionException) {
            $expectedDto = Helper::getConstructorFirstParameterClassName($this);

            throw new AddDtoException($this::class, $expectedDto, $item::class);
        }
    }
}
