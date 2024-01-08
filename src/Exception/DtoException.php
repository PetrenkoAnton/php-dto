<?php

declare(strict_types=1);

namespace Dto\Exception;

class DtoException extends \Exception
{
    public const NO_TYPE_DECLARATION_CODE = 101;
    public const NULLABLE_DECLARATION_CODE = 102;
    public const MIXED_DECLARATION_CODE = 103;
    public const OBJECT_DECLARATION_CODE = 104;
    public const NOT_DTO_CLASS_DECLARATION_CODE = 105;
    public const ENUM_NO_BACKING_VALUE_CODE = 106;

    public function __construct(string $message, int $code)
    {
        parent::__construct(
            message: $message,
            code: $code,
        );
    }
}
