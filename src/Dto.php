<?php

declare(strict_types=1);

namespace Dto;

use Dto\Common\ArrayableInterface;
use Dto\Exception\DeclarationException;
use Dto\Exception\DeclarationExceptions\MixedDeclarationException;
use Dto\Exception\DeclarationExceptions\NotDtoClassDeclarationException;
use Dto\Exception\DeclarationExceptions\NoTypeDeclarationException;
use Dto\Exception\DeclarationExceptions\NullableDeclarationException;
use Dto\Exception\DeclarationExceptions\ObjectDeclarationException;
use Dto\Exception\GetValueException;
use Dto\Exception\InputDataException;
use Dto\Exception\SetValueException;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use UnitEnum;

abstract class Dto implements DtoInterface
{
    /**
     * @var ReflectionProperty[]
     */
    private array $properties;

    /**
     * @throws DeclarationException
     * @throws SetValueException
     * @throws InputDataException
     */
    public function __construct(array $data)
    {
        $this->properties = (new \ReflectionClass($this))->getProperties(\ReflectionProperty::IS_PROTECTED);

        foreach ($this->properties as $property) {
            $name = $property->getName();

            if (!key_exists($name, $data))
                throw new InputDataException($this::class, $name);

            $type = $property->getType();
            $value = $data[$name] ?? null;

            $this->validateDeclaration($property);

            try {
                $this->setValue($type, $name, $value);
            } catch (\Throwable) {
                throw new SetValueException($this::class, $name, $type->getName(), gettype($value), $value);
            }
        }
    }

    /**
     * @throws GetValueException
     */
    public function __call(string $name, array $arguments): mixed
    {
        try {
            $property = $this->resolveExpectedProperty($name);
        } catch (\InvalidArgumentException $e) {
            throw new GetValueException($this::class, $e->getMessage());
        }

        return $this->$property;
    }

    public function toArray(): array
    {
        $properties = (new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PROTECTED);

        $data = [];

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($this);

            $data[$property->getName()] = $value instanceof ArrayableInterface ? $value->toArray() : $value;
        }

        return $data;
    }

    /**
     * @throws DeclarationException
     */
    private function validateDeclaration(ReflectionProperty $property): void
    {
        $type = $property->getType();
        $name = $property->getName();

        if (\is_null($type))
            throw new NoTypeDeclarationException($this::class, $name);

        if ($type->getName() === 'mixed')
            throw new MixedDeclarationException($this::class, $name);

        if ($type->allowsNull())
            throw new NullableDeclarationException($this::class, $name);

        if ($type->getName() === 'object')
            throw new ObjectDeclarationException($this::class, $name);

        if (!$type->isBuiltin() && \is_subclass_of($type->getName(), UnitEnum::class))
            return;

        if (!$type->isBuiltin() && \is_subclass_of($type->getName(), DtoCollection::class))
            return;

        if (!$type->isBuiltin() && !\is_subclass_of($type->getName(), DtoInterface::class))
            throw new NotDtoClassDeclarationException($this::class, $name);
    }

    private function resolveExpectedProperty(string $method): string
    {
        if (\str_starts_with($method, 'is')) {
            $expectedProperty = \lcfirst(\substr($method, 2));

            return $this->getExpectedProperty($expectedProperty);
        }

        if (\str_starts_with($method, 'get')) {
            $expectedProperty = \lcfirst(\substr($method, 3));

            return $this->getExpectedProperty($expectedProperty);
        }

        throw new InvalidArgumentException("Unexpected method: $method");
    }

    private function getExpectedProperty(string $expectedProperty): string
    {
        foreach ($this->properties as $property) {
            if ($property->getName() === $expectedProperty)
                return $expectedProperty;
        }

        throw new InvalidArgumentException("Unexpected property: $expectedProperty");
    }

    /**
     * @throws ReflectionException
     */
    private function setValue(\ReflectionType $propertyType, string $propertyName, mixed $value): void
    {
        /** @var string $typeName */
        $typeName = $propertyType->getName();

        if ($value instanceof ArrayableInterface) {
            $value = $value->toArray();
        }

        if ($propertyType->isBuiltin()) {
            $this->setBuiltinType($typeName, $propertyName, $value);
            return;
        }

        if (\is_subclass_of($typeName, UnitEnum::class)) {
            $this->setEnumValue($typeName, $propertyName, $value);
            return;
        }

        if (!\is_array($value)) {
            $value = [];
        }

        if (\is_subclass_of($typeName, DtoCollection::class)) {
            $this->setDtoCollectionType($typeName, $propertyName, $value);
            return;
        }

        $this->{$propertyName} = $this->createObject($typeName, $value);
    }

    private function setBuiltinType(string $typeName, string $propertyName, mixed $value): void
    {
        if ($typeName === 'array' && !\is_array($value)) {
            $this->{$propertyName} = [];
            return;
        }

        // TODO!
        // \settype($value, $typeName);
        $this->{$propertyName} = $value;
    }

    /**
     * @throws ReflectionException
     */
    private function setDtoCollectionType(string $typeName, string $propertyName, array $values): void
    {
        $collection = new $typeName();
        $dto = (new ReflectionClass($typeName))->getConstructor()->getParameters()[0]->getType()->getName();

        foreach ($values as $value) {
            $item = $this->createObject($dto, $value);
            $collection->add($item);
        }

        $this->{$propertyName} = $collection;
    }

    private function setEnumValue(string $typeName, string $propertyName, mixed $value): void
    {
        $this->{$propertyName} = $typeName::from($value);
    }

    private function createObject(string $class, array $value): object
    {
        return new $class($value);
    }
}
