<?php

declare(strict_types=1);

namespace Dto;

final class InvalidItemTypeCollectionException extends CollectionException
{
    public function __construct(string $collection, string $expected, string $given)
    {
        parent::__construct(
            message: \sprintf(
                'Collection: %s | Expected item type: %s | Given: %s',
                $collection,
                $expected,
                $given
            ),
            code: self::INVALID_ITEM_TYPE,
        );
    }
}
