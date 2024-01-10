<?php

declare(strict_types=1);

namespace Dto;

final class InvalidKeyCollectionException extends CollectionException
{
    public function __construct(string $collection, int $key)
    {
        parent::__construct(
            message: \sprintf(
                'Collection: %s | Invalid key: %d',
                $collection,
                $key,
            ),
            code: self::INVALID_KEY,
        );
    }
}
