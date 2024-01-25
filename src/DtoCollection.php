<?php

declare(strict_types=1);

namespace Dto;

use Collection\Collectable;
use Collection\Collection;
use Collection\Exception\CollectionException\InvalidConstructorDeclarationException;
use Collection\Exception\CollectionException\InvalidItemTypeException;
use Collection\Helper;
use Dto\Exception\DtoException;
use Dto\Exception\DtoException\InitDtoException\DtoCollectionConstructorException;
use Dto\Exception\DtoException\SetupDtoException\AddDtoException;

abstract class DtoCollection extends Collection
{
    public function __construct(Dto ...$items)
    {
        parent::__construct(...$items);
    }

    /**
     * @throws DtoException
     */
    public function add(Collectable $item): void
    {
        try {
            parent::add($item);
        } catch (InvalidItemTypeException) {
            throw new AddDtoException($this::class, Helper::getConstructorFirstParameterClassName($this), $item::class);
        } catch (InvalidConstructorDeclarationException) {
            throw new DtoCollectionConstructorException($this::class);
        }
    }
}
