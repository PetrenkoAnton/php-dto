<?php /** @noinspection PhpCSValidationInspection */
/** @noinspection PhpCSValidationInspection */

declare(strict_types=1);

namespace Dto\Exception\Internal;

use Exception;

final class EnumBackingValueException extends Exception
{
    public function __construct(private array $values)
    {
        parent::__construct();
    }

    public function getData(): array
    {
        return $this->values;
    }
}
