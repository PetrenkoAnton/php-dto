<?php

declare(strict_types=1);

namespace Dto;

use Dto\Common\ArrayableInterface;
use Dto\Exception\DeclarationException;
use Dto\Exception\DeclarationExceptions\MixedDeclarationException;
use Dto\Exception\DeclarationExceptions\NoTypeDeclarationException;
use Dto\Exception\DeclarationExceptions\NullableDeclarationException;
use Dto\Exception\DeclarationExceptions\ObjectDeclarationException;
use Dto\Exception\GetValueException;
use Dto\Exception\SetValueException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

abstract class Dto implements DtoInterface
{
    /**
     * @var ReflectionProperty[]
     */
    private array $properties;

    /**
     * @throws DeclarationException
     * @throws SetValueException
     */
    public function __construct(array $data)
    {
        $this->properties = (new \ReflectionClass($this))->getProperties(\ReflectionProperty::IS_PROTECTED);

        foreach ($this->properties as $property) {
            $name = $property->getName();
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
        } catch (\InvalidArgumentException) {
            // TODO!
            throw new GetValueException($this::class, '!property!');
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

    protected function enableEmpty(): bool
    {
        return false;
    }

    /**
     * @throws DeclarationException
     */
    private function validateDeclaration(ReflectionProperty $property): void
    {
        if (\is_null($property->getType()))
            throw new NoTypeDeclarationException($this::class, $property->getName());

        if ($property->getType()->getName() === 'mixed')
            throw new MixedDeclarationException($this::class, $property->getName());

        if ($property->getType()->allowsNull())
            throw new NullableDeclarationException($this::class, $property->getName());

        if ($property->getType()->getName() === 'object')
            throw new ObjectDeclarationException($this::class, $property->getName());
    }

    protected function resolveExpectedProperty(string $method): string
    {
        if (\str_starts_with($method, 'is')) {
            $expectedProperty = \lcfirst(\substr($method, 2));

            return $this->getExpectedProperty($expectedProperty, $method);
        }

        if (\str_starts_with($method, 'get')) {
            $expectedProperty = \lcfirst(\substr($method, 3));

            return $this->getExpectedProperty($expectedProperty, $method);
        }

        // TODO! Temporary handler. Delete before production ready
        var_dump(666);
        die;

        throw new \InvalidArgumentException("Unexpected method name: {$method}");
    }

    private function getExpectedProperty(string $expectedProperty, string $method): string
    {
        foreach ($this->properties as $property) {
            if ($property->getName() === $expectedProperty)
                return $expectedProperty;
        }

        throw new \InvalidArgumentException("Unexpected method name: {$method}");
    }

    /**
     * @throws ReflectionException
     */
    protected function setValue(\ReflectionType $propertyType, string $propertyName, mixed $value): void
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

    private function createObject(string $class, array $value): object
    {
        return new $class($value);
    }
}
