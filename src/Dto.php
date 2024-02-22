<?php

declare(strict_types=1);

namespace Dto;

use Collection\Arrayable;
use Collection\Collectable;
use Collection\Helper;
use Dto\Exception\DtoException;
use Dto\Exception\DtoException\HandleDtoException\GetValueException;
use Dto\Exception\DtoException\InitDtoException\DeclarationException;
use Dto\Exception\DtoException\InitDtoException\DeclarationException\EnumNoBackingValueException;
use Dto\Exception\DtoException\InitDtoException\DeclarationException\MixedDeclarationException;
use Dto\Exception\DtoException\InitDtoException\DeclarationException\NoTypeDeclarationException;
use Dto\Exception\DtoException\InitDtoException\DeclarationException\NotDtoClassDeclarationException;
use Dto\Exception\DtoException\InitDtoException\DeclarationException\ObjectDeclarationException;
use Dto\Exception\DtoException\SetupDtoException\InputDataException;
use Dto\Exception\DtoException\SetupDtoException\SetValueEnumException;
use Dto\Exception\DtoException\SetupDtoException\SetValueException;
use Dto\Exception\Internal\EnumBackingValueException;
use Error;
use InvalidArgumentException;
use KeyNormalizer\KeyNormalizer;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;
use Throwable;
use UnitEnum;
use ValueError;

use function array_column;
use function gettype;
use function implode;
use function is_a;
use function is_array;
use function is_null;
use function is_subclass_of;
use function key_exists;
use function lcfirst;
use function str_starts_with;
use function substr;

abstract class Dto implements Arrayable, Collectable
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

            if (!key_exists($name, $data)) {
                throw new InputDataException($this::class, $name);
            }

            /** @var ReflectionNamedType $type */
            $type = $property->getType();

            $value = $data[$name] ?? null;

            $this->validateDeclaration($property);

            try {
                $this->setValue($type, $name, $value);
            } catch (EnumBackingValueException $e) {
                $expectedValues = implode(', ', $e->getData());

                throw new SetValueEnumException(
                    dto: $this::class,
                    property: $name,
                    type: $type->getName(),
                    expectedValues: $expectedValues,
                    givenType: gettype($value),
                    value: $value,
                );
            } catch (EnumNoBackingValueException $e) {
                throw $e;
            } catch (Throwable $e) {
                throw new SetValueException(
                    dto: $this::class,
                    property: $name,
                    expectedType: $type->getName(),
                    givenType: gettype($value),
                    value: $value,
                );
            }
        }
    }

    /**
     * @throws GetValueException
     *
     * @psalm-suppress PossiblyUnusedParam
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

    public function toArray(KeyCase $keyCase = KeyCase::CAMEL_CASE): array
    {
        $properties = (new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PROTECTED);

        $data = [];

        foreach ($properties as $property) {
            /** @psalm-suppress UnusedMethodCall */
            $property->setAccessible(true);
            $value = $property->getValue($this);

            $key = match ($keyCase) {
                KeyCase::SNAKE_CASE => KeyNormalizer::toSnakeCase($property->getName()),
                KeyCase::CAMEL_CASE => $property->getName(),
            };

            $data[$key] = is_a($value, Arrayable::class)
                ? is_a($value, self::class) ? $value->toArray($keyCase) : $value->toArray()
                : $value;
        }

        return $data;
    }

    /**
     * @throws DeclarationException
     */
    private function validateDeclaration(ReflectionProperty $property): void
    {
        $name = $property->getName();

        if (!$property->getType()) {
            throw new NoTypeDeclarationException($this::class, $name);
        }

        /** @var ReflectionNamedType $type */
        $type = $property->getType();

        if ($type->getName() === 'mixed') {
            throw new MixedDeclarationException($this::class, $name);
        }

        if ($type->getName() === 'object') {
            throw new ObjectDeclarationException($this::class, $name);
        }

        if (!$type->isBuiltin() && is_subclass_of($type->getName(), UnitEnum::class)) {
            return;
        }

        if (!$type->isBuiltin() && is_subclass_of($type->getName(), DtoCollection::class)) {
            return;
        }

        if (!$type->isBuiltin() && !is_subclass_of($type->getName(), self::class)) {
            throw new NotDtoClassDeclarationException($this::class, $name);
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    private function resolveExpectedProperty(string $method): string
    {
        if (str_starts_with($method, 'is')) {
            $expectedProperty = lcfirst(substr($method, 2));

            return $this->getExpectedProperty($expectedProperty);
        }

        if (str_starts_with($method, 'get') && $expectedProperty = lcfirst(substr($method, 3))) {
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
            if ($property->getName() === $expectedProperty) {
                return $expectedProperty;
            }
        }

        throw new InvalidArgumentException("Unexpected property: $expectedProperty");
    }

    /**
     * @throws ReflectionException
     * @throws DtoException
     * @throws EnumBackingValueException
     */
    private function setValue(ReflectionNamedType $propertyType, string $propertyName, mixed $value): void
    {
        if (is_null($value) && $propertyType->allowsNull()) {
            $this->{$propertyName} = null;

            return;
        }

        if (is_a($value, Arrayable::class)) {
            $value = $value->toArray();
        }

        $typeName = $propertyType->getName();

        if ($propertyType->isBuiltin()) {
            $this->setBuiltinType($typeName, $propertyName, $value);

            return;
        }

        if (is_subclass_of($typeName, UnitEnum::class)) {
            $this->setEnumValue($typeName, $propertyName, $value);

            return;
        }

        if (is_subclass_of($typeName, DtoCollection::class)) {
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
        if ($typeName === 'array' && !is_array($value)) {
            throw new InvalidArgumentException();
        }

        $this->{$propertyName} = $value;
    }

    private function setDtoCollectionType(string $typeName, string $propertyName, array $values): void
    {
        $collection = new $typeName();

        $class = Helper::getConstructorFirstParameterClassName($collection);

        foreach ($values as $value) {
            $item = $this->createObject($class, $value);
            $collection->add($item);
        }

        $this->{$propertyName} = $collection;
    }

    /**
     * @throws EnumBackingValueException
     * @throws EnumNoBackingValueException
     */
    private function setEnumValue(string | UnitEnum $typeName, string $propertyName, mixed $value): void
    {
        try {
            /**
             * @psalm-suppress UndefinedMethod
             */
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
