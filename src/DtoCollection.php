<?php

declare(strict_types=1);

namespace Dto;

use Dto\Common\Collection;
use Dto\Exception\SetValueException\InvalidAddMethodArgument;
use Dto\Exception\SetValueException\InvalidConstructorVariadicParams;
use Dto\Exception\SetValueException\InvalidNumberOfArguments;
use ReflectionClass;
use ReflectionException;

abstract class DtoCollection extends Collection
{
    protected array $items = [];

    /**
     * @throws ReflectionException
     * @throws InvalidConstructorVariadicParams
     */
    public function __construct()
    {
        $this->validateConstructorParameters();
    }

    /**
     * @throws InvalidAddMethodArgument
     * @throws InvalidNumberOfArguments
     */
    public function __call(string $name, array $arguments): void
    {
        if ($name === 'add') {
            $this->handleAddMethod($arguments);
        }
    }

    /**
     * @throws InvalidAddMethodArgument
     * @throws InvalidNumberOfArguments
     */
    private function handleAddMethod(array $arguments): void
    {
        $this->validateArguments($arguments);

        $this->items[] = $arguments[0];
    }

    /**
     * @throws ReflectionException
     * @throws InvalidConstructorVariadicParams
     */
    private function validateConstructorParameters(): void
    {
        $params = (new ReflectionClass($this))->getConstructor()->getParameters();

        if (\count($params) !== 1 || !$params[0]->isVariadic())
            throw new InvalidConstructorVariadicParams();

        $className = $params[0]->getType()->getName();

        $isDto = (new ReflectionClass($className))->implementsInterface(DtoInterface::class);

        if (!$isDto)
            throw new InvalidConstructorVariadicParams();
    }

    /**
     * @throws InvalidAddMethodArgument
     * @throws InvalidNumberOfArguments
     */
    private function validateArguments(array $arguments): void
    {
        if (\count($arguments) != 1)
            throw new InvalidNumberOfArguments();

        if (!\is_object($arguments[0]))
            throw new InvalidAddMethodArgument();

        $argumentClassName = \get_class($arguments[0]);
        $dtoClassName = (new ReflectionClass($this))->getConstructor()->getParameters()[0]->getType()->getName();

        if ($argumentClassName !== $dtoClassName)
            throw new InvalidAddMethodArgument();
    }
}