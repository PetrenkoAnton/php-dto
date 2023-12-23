<?php

declare(strict_types=1);

namespace Dto;

use Dto\Exception\GetValueException;
use Dto\Exception\SetValueException;

abstract class Dto implements DtoInterface
{
    /**
     * @var \ReflectionProperty[]
     */
    private array $properties;

    /**
     * @throws SetValueException
     */
    public function __construct(array $data)
    {
        $this->properties = (new \ReflectionClass($this))->getProperties(\ReflectionProperty::IS_PROTECTED);

        foreach ($this->properties as $property) {
            $propertyName = $property->getName();
            $propertyType = $property->getType();
            $value = $data[$propertyName] ?? null;

            try {
                $this->setValue($propertyType, $propertyName, $value);
            } catch (\Throwable $throwable) {
                throw new SetValueException($this::class, $propertyName, $value, $throwable); // TODO: improve
            }
        }
    }

    public function __call(string $name, array $arguments): mixed
    {
        try {
            $property = $this->resolveExpectedProperty($name);
        } catch (\InvalidArgumentException $e) {
            throw new GetValueException($this::class, $name, $e);
        }

        return $this->$property;
    }

    protected function resolveExpectedProperty(string $method): string
    {
        if (\str_starts_with($method, 'is')) {
            $expectedProperty = \lcfirst(\substr($method, 2));

            return $this->_getExpectedProperty($expectedProperty, $method);
        }

        if (\str_starts_with($method, 'get')) {
            $expectedProperty = \lcfirst(\substr($method, 3));

            return $this->_getExpectedProperty($expectedProperty, $method);
        }

        // TODO! Temporary handler. Delete before production ready
        var_dump(666);
        die;

        throw new \InvalidArgumentException("Unexpected method name: {$method}");
    }

    private function _getExpectedProperty(string $expectedProperty, string $method): string
    {
        foreach ($this->properties as $property) {
            if ($property->getName() === $expectedProperty)
                return $expectedProperty;
        }

        throw new \InvalidArgumentException("Unexpected method name: {$method}");
    }

    ////

    protected function setValue(?\ReflectionType $propertyType, string $propertyName, mixed $value): void
    {
        if ($propertyType === null) {
            $this->setWithoutType($propertyName, $value);
            return;
        }

        if ($propertyType->allowsNull() && $value === null) {
            $this->setWithoutType($propertyName, null);
            return;
        }

        $this->setTypedValue($propertyType, $propertyName, $value);
    }

    protected function setTypedValue(\ReflectionType $propertyType, string $propertyName, mixed $value): void
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

        $this->setObjectType($typeName, $propertyName, $value);
    }

    protected function setWithoutType(string $propertyName, mixed $value): void
    {
        $this->{$propertyName} = $value;
    }

    private function setBuiltinType(string $typeName, string $propertyName, mixed $value): void
    {
        if ($typeName === 'array' && !\is_array($value)) {
            $this->{$propertyName} = [];
            return;
        }

        \settype($value, $typeName);
        $this->{$propertyName} = $value;
    }

    private function setDtoCollectionType(string $typeName, string $propertyName, array $value): void
    {
        /** @var DtoCollection $collection */
        $collection = new $typeName();
        foreach ($value as $v) {
            $itemClassProperty = (new \ReflectionClass($collection))->getProperty('entityClass');
            $itemClassProperty->setAccessible(true);
            $itemClass = $itemClassProperty->getValue($collection);
            $item = $v instanceof $itemClass ? $v : $this->createObject($itemClass, $this->prepareCollectionItem($v));
            $collection->add($item);
        }
        $this->{$propertyName} = $collection;
    }

    /**
     * @param array|ArrayableInterface|mixed $value
     */
    private function prepareCollectionItem(mixed $value): array
    {
        if (\is_array($value)) {
            return $value;
        }

        if ($value instanceof ArrayableInterface) {
            return $value->toArray();
        }

        return [];
    }

    private function setObjectType(string $typeName, string $propertyName, array $value): void
    {
        $this->{$propertyName} = $this->createObject($typeName, $value);
    }

    private function createObject(string $class, array $value): object
    {
        if (!\is_subclass_of($class, DtoInterface::class)) {
            throw new \TypeError("Class $class not allowed, only Dto classes allowed");
        }
        return new $class($value);
    }

    public function toArray(): array
    {
        $data = \get_object_vars($this);
        foreach ($data as $key => $val) {
            $accessor = $this->resolveAccessor($key);
            if (\method_exists($this, $accessor)) {
                $data[$key] = $this->$accessor();
            }
            if ($data[$key] instanceof ArrayableInterface) {
                $data[$key] = $data[$key]->toArray();
            }
        }
        return $data;
    }

    protected function resolveAccessor(string $key): string
    {
        $key = $this->normalizeKey($key);

        if (str_starts_with($key, 'is') && \method_exists($this, $key)) {
            return $key;
        }

        $accessor = 'is' . \ucfirst($key);
        if (\method_exists($this, $accessor)) {
            return $accessor;
        }

        return 'get' . \ucfirst($key);
    }

    protected function normalizeKey(string $key): string
    {
        return \lcfirst(\str_replace('_', '', \ucwords($key, '_')));
    }
}
