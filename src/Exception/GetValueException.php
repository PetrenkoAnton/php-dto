<?php

declare(strict_types=1);

namespace Dto\Exception;

class GetValueException extends \Exception
{
    public function __construct()
    {
        parent::__construct(
            \sprintf(
                'Get value error'
            )
        );
    }
}
