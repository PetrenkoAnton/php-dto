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

    public const ADD_DTO_EXCEPTION = 201;
    public const EMPTY_INPUT_DATA_CODE = 202;
    public const SET_INVALID_VALUE_CODE = 203;
    public const SET_INVALID_ENUM_VALUE_CODE = 204;
    public const GET_VALUE_EXCEPTION_CODE = 205;

    public function __construct(string $message, int $code)
    {
        parent::__construct(
            message: $message,
            code: $code,
        );
    }
}
