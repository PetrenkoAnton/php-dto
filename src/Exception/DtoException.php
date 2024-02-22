<?php

declare(strict_types=1);

namespace Dto\Exception;

use Exception;

class DtoException extends Exception
{
    // INIT
    protected const NO_TYPE_DECLARATION_CODE = 101;
    protected const MIXED_DECLARATION_CODE = 102;
    protected const OBJECT_DECLARATION_CODE = 103;
    protected const NOT_DTO_CLASS_DECLARATION_CODE = 104;
    protected const ENUM_NO_BACKING_VALUE_CODE = 105;
    protected const INVALID_DTO_COLLECTION_CONSTRUCTOR_CODE = 106;
    // SETUP
    protected const ADD_DTO_EXCEPTION = 201;
    protected const EMPTY_INPUT_DATA_CODE = 202;
    protected const SET_INVALID_VALUE_CODE = 203;
    protected const SET_INVALID_ENUM_VALUE_CODE = 204;
    // HANDLE
    protected const GET_VALUE_EXCEPTION_CODE = 301;

    public function __construct(string $message, int $code)
    {
        parent::__construct(
            message: $message,
            code: $code,
        );
    }
}
