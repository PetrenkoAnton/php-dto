<?php

declare(strict_types=1);

namespace Dto;

use Collection\Arrayable;
use Collection\Collectable;
use Dto\Exception\DtoException;
use Dto\Exception\DtoException\HandleDtoException\GetValueException;
use Dto\Exception\DtoException\InitDtoException\DeclarationException;
use Dto\Exception\DtoException\InitDtoException\DeclarationException\EnumNoBackingValueException;
use Dto\Exception\DtoException\InitDtoException\DeclarationException\MixedDeclarationException;
use Dto\Exception\DtoException\InitDtoException\DeclarationException\NotDtoClassDeclarationException;
use Dto\Exception\DtoException\InitDtoException\DeclarationException\NoTypeDeclarationException;
use Dto\Exception\DtoException\InitDtoException\DeclarationException\NullableDeclarationException;
use Dto\Exception\DtoException\InitDtoException\DeclarationException\ObjectDeclarationException;
use Dto\Exception\DtoException\SetupDtoException\InputDataException;
use Dto\Exception\DtoException\SetupDtoException\SetValueEnumException;
use Dto\Exception\DtoException\SetupDtoException\SetValueException;
use Dto\Exception\Internal\EnumBackingValueException;
use Error;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use UnitEnum;
use ValueError;

abstract class Dto implements Collectable, Arrayable
{
    /**
     * @var ReflectionProperty[]
     */
    private array $properties;

    /**
     * @throws DtoException
     */
    public function __construct(array $data)
    {
        $this->properties = (new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PROTECTED);

        foreach ($this->properties as $property) {
            $name = $property->getName();

            if (!\key_exists($name, $data))
                throw new InputDataException($this::class, $name);

            $type = $property->getType();
            $value = $data[$name] ?? null;

            $this->validateDeclaration($property);

            try {
                $this->setValue($type, $name, $value);
            }
            catch (EnumBackingValueException $e) {
                $expectedValues = \implode(', ', $e->getData());

                throw new SetValueEnumException(
                    dto: $this::class,
                    property: $name,
                    type: $type->getName(),
                    expectedValues: $expectedValues,
                    givenType: gettype($value),
                    value: $value
                );
            }
            catch (EnumNoBackingValueException $e) {
                throw $e;
            }
            catch (\Throwable) {
                throw new SetValueException(
                    dto: $this::class,
                    property: $name,
                    expectedType: $type->getName(),
                    givenType: gettype($value),
                    value: $value
                );
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
        } catch (InvalidArgumentException $e) {
            throw new GetValueException($this::class, $e->getMessage());
        }

        return $this->{$property};
    }

    public function toArray(): array
    {
        $properties = (new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PROTECTED);

        $data = [];

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($this);

            $data[$property->getName()] = \is_subclass_of($value, Arrayable::class)
                ? $value->toArray()
                : $value;
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

        if (!$type)
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

        if (!$type->isBuiltin() && !\is_subclass_of($type->getName(), Dto::class))
            throw new NotDtoClassDeclarationException($this::class, $name);
    }

    /**
     * @throws InvalidArgumentException
     */
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

    /**
     * @throws InvalidArgumentException
     */
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
     * @throws DtoException
     * @throws EnumBackingValueException
     */
    private function setValue(\ReflectionType $propertyType, string $propertyName, mixed $value): void
    {
        /** @var string $typeName */
        $typeName = $propertyType->getName();

        if (\is_subclass_of($value, Collectable::class))
            $value = $value->toArray();

        if ($propertyType->isBuiltin()) {
            $this->setBuiltinType($typeName, $propertyName, $value);
            return;
        }

        if (\is_subclass_of($typeName, UnitEnum::class)) {
            $this->setEnumValue($typeName, $propertyName, $value);
            return;
        }

        if (\is_subclass_of($typeName, DtoCollection::class)) {
            $this->setDtoCollectionType($typeName, $propertyName, $value);
            return;
        }

        $this->{$propertyName} = $this->createObject($typeName, $value);
    }

    /**
     * @throws InvalidArgumentException
     */
    private function setBuiltinType(string $typeName, string $propertyName, mixed $value): void
    {
        if ($typeName === 'array' && !\is_array($value))
            throw new InvalidArgumentException();

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

    /**
     * @throws EnumBackingValueException
     * @throws EnumNoBackingValueException
     */
    private function setEnumValue(string $typeName, string $propertyName, mixed $value): void
    {
        /** @var UnitEnum $typeName */
        try {
            $this->{$propertyName} = $typeName::from($value);
        } catch (ValueError) {
            throw new EnumBackingValueException(array_column($typeName::cases(), 'value'));
        } catch (Error) {
            throw new EnumNoBackingValueException($this::class, $propertyName);
        }
    }

    private function createObject(string $class, array $value): object
    {
        return new $class($value);
    }
}
