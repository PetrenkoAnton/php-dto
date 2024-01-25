# php-dto

[![PHP Version](https://img.shields.io/packagist/php-v/petrenkoanton/php-dto)](https://packagist.org/packages/petrenkoanton/php-dto)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/petrenkoanton/php-dto.svg)](https://packagist.org/packages/petrenkoanton/php-dto)
[![Total Downloads](https://img.shields.io/packagist/dt/petrenkoanton/php-dto.svg)](https://packagist.org/packages/petrenkoanton/php-dto)
[![License](https://img.shields.io/packagist/l/petrenkoanton/php-dto)](https://packagist.org/packages/petrenkoanton/php-dto)

[![PHP Composer](https://github.com/petrenkoanton/php-dto/actions/workflows/tests.yml/badge.svg)](https://github.com/petrenkoanton/php-dto/actions/workflows/tests.yml)
[![Coverage Status](https://coveralls.io/repos/github/petrenkoanton/php-dto/badge.svg)](https://coveralls.io/github/petrenkoanton/php-dto)
[![type-coverage](https://shepherd.dev/github/petrenkoanton/php-dto/coverage.svg)](https://shepherd.dev/github/petrenkoanton/php-dto)
[![psalm-level](https://shepherd.dev/github/petrenkoanton/php-dto/level.svg)](https://shepherd.dev/github/petrenkoanton/php-dto)
[![Build Status](https://github.com/petrenkoanton/php-dto/workflows/coding-style/badge.svg)](https://github.com/petrenkoanton/php-dto/actions)

[Installation](#installation) | [Functionality](#functionality) | [Usage](#usage) | [For developers](#for-developers)
| [License](#license) | [Related projects](#related-projects)

## Installation

### Requirements

- php 8.1 or higher

### Composer

```bash
composer require petrenkoanton/php-dto
```

## Functionality

### Public methods

#### [Dto](./src/Dto.php)

All getters are provided using the `__call()` magic method.

| Method                                        | Exception    |
|:----------------------------------------------|:-------------|
| __construct(array $data)                      | DtoException |
| __call(string $name, array $arguments): mixed | DtoException |
| toArray(): array                              | -            |

#### [DtoCollection](./src/DtoCollection.php)

| Method                                 | Exception           |
|:---------------------------------------|:--------------------|
| __construct(Dto ...$items)             | DtoException        |
| add(Collectable $item): void           | DtoException        |

#### Parent [Collection](https://github.com/PetrenkoAnton/php-collection)
[github.com/PetrenkoAnton/php-collection](https://github.com/PetrenkoAnton/php-collection)

| Method                                 | Exception           |
|:---------------------------------------|:--------------------|
| filter(callable $callback): Collection | -                   |
| getItems(): array                      | -                   |
| getItem(int $key): Collectable         | CollectionException |
| first(): Collectable                   | CollectionException |
| count(): int                           | -                   |

### Exceptions

Main library exception is [DtoException](./src/Exception/DtoException.php).

There are [3 groups of exceptions](./src/Exception/DtoException): InitDtoException, SetupDtoException and 
HandleDtoException

| Code | Message pattern                                                                                               | Exception                         | Group              |
|------|:--------------------------------------------------------------------------------------------------------------|:----------------------------------|:-------------------|
| 101  | Dto: %s &#124; Property: %s &#124; Err: Missed property type declaration                                      | NoTypeDeclarationException        | InitDtoException   |
| 102  | Dto: %s &#124; Property: %s &#124; Err: Unsupported nullable property type declaration                        | NullableDeclarationException      | InitDtoException   |
| 103  | Dto: %s &#124; Property: %s &#124; Err: Unsupported mixed property type declaration                           | MixedDeclarationException         | InitDtoException   |
| 104  | Dto: %s &#124; Property: %s &#124; Err: Unsupported object property type declaration                          | ObjectDeclarationException        | InitDtoException   |
| 105  | Dto: %s &#124; Property: %s &#124; Err: Class must implement DtoInterface                                     | NotDtoClassDeclarationException   | InitDtoException   |
| 106  | Dto: %s &#124; Property: %s &#124; Err: No backing value for enum                                             | EnumNoBackingValueException       | InitDtoException   |
| 107  | DtoCollection: %s &#124; Err: Invalid constructor declaration                                                 | DtoCollectionConstructorException | InitDtoException   |
| 201  | DtoCollection: %s &#124; Expected Dto: %s &#124; Given Dto: %s                                                | AddDtoException                   | SetupDtoException  |
| 202  | Dto: %s &#124; Property: %s &#124; Err: No data                                                               | InputDataException                | SetupDtoException  |
| 203  | Dto: %s &#124; Property: %s &#124; Expected type: %s &#124; Given type: %s &#124; Value: %s                   | SetValueException                 | SetupDtoException  |
| 204  | Dto: %s &#124; Property: %s &#124; Enum: %s &#124; Expected values: %s &#124; Given type: %s &#124; Value: %s | SetValueEnumException             | SetupDtoException  |
| 301  | Dto: %s &#124; %s                                                                                             | GetValueException                 | HandleDtoException |

## Usage

### Initialization

- Your dto class must extends `Dto\Dto` abstract class.
- You need to declare available `protected` properties. 

**Important!** Getter will be with the prefix `is*` if property is a `bool` type.

#### Simple DTO

```php
<?php

declare(strict_types=1);

use Dto\Dto;

/**
 * @method int getPrice()
 * @method string getType()
 * @method array getInfo()
 * @method bool isAvailable()
 */
class ProductDto extends Dto
{
    protected int $price;
    protected string $type;
    protected array $info;
    protected bool $available;
}

// Array or instance of Arrayable interface
$info = [
    'key' => 'value',
];

$data = [
    'price' => 999,
    'type' => 'ticket',
    'info' => $info,
    'available' => true,
];

$dto = new ProductDto($data);

$price = $dto->getPrice(); // 999
$type = $dto->getType(); // 'ticket'
$info = $dto->getInfo(); // ['key' => 'value']
$available = $dto->isAvailable(); // true
```

#### Nested DTO (with Collection and Enum)

```php
<?php

declare(strict_types=1);

use Collection\Arrayable;
use Dto\Dto;
use Dto\DtoCollection;

/**
 * @method string getName()
 * @method int getAge()
*/
class PersonDto extends Dto
{
    protected string $name;
    protected int $age;
}

class PersonDtoCollection extends DtoCollection
{
    public function __construct(PersonDto ...$items)
    {
        parent::__construct(...$items);
    }
}

/**
 * @method int getPrice()
 * @method string getType()
 * @method array getInfo()
 * @method bool isAvailable()
 */
class ProductDto extends Dto
{
    protected int $price;
    protected string $type;
    protected array $info;
    protected bool $available;
}

enum ColorEnum: string
{
    case Red = 'red';
    case Black = 'black';
    case White = 'white';
}

/**
 * @method PersonDtoCollection getPersons()
 * @method ProductDto getProduct()
 * @method ColorEnum getColor()
 */
class NestedDto extends Dto
{
    protected PersonDtoCollection $persons;
    protected ProductDto $product;
    protected ColorEnum $color;
}

class NestedDtoFactory
{
    public function create(array $data): NestedDto
    {
        return new NestedDto($data);
    }
}

class InfoArrayable implements Arrayable
{
    public function toArray(): array
    {
        return [
            'key' => 'value',
        ];
    }
}

$data = [
    'persons' => [
        [
            'name' => 'Alice',
            'age' => 25,
        ],
        [
            'name' => 'Bob',
            'age' => 30,
        ],
    ],
    'product' => [
        'price' => 999,
        'type' => 'ticket',
        'info' => new InfoArrayable(),
        'available' => true,
    ],
    'color' => 'red',
];

$nestedDto = (new NestedDtoFactory())->create($data);

$personsCount = $nestedDto->getPersons()->count() // 2

$aliceDto = $nestedDto->getPersons()->first();
$aliceName = $aliceDto->getName(); // 'Alice'
$aliceAge = $aliceDto->getAge(); // 25

$bobDto = $nestedDto->getPersons()->filter(
    fn (PersonDto $personDto) => $personDto->getName() === 'Bob'
)->first();
$bobName = $bobDto->getName(); // 'Bob'
$bobAge = $bobDto->getAge(); // '30'

$productDto = $nestedDto->getProduct();
$productPrice = $productDto->getPrice(); // 999
$productInfo = $productDto->getInfo(); // ['key' => 'value']

$color = $nestedDto->getColor(); // ColorEnum::Red
$colorValue = $colorEnum->value; // 'red'
```

## For developers

### Requirements

Utils:
- make
- [docker-compose](https://docs.docker.com/compose/gettingstarted)

### Setup

#### Initialize

Create `./docker/.env`
```bash
make init 
```

#### Build container with the different php version

php 8.1
```bash
make up81 
```

php 8.2
```bash
make up82
```

php 8.3
```bash
make up83
```

Also you need to run this command before build container with another php version.
It will remove network and previously created container.
```bash
make down
```

#### Other commands

Go inside of the container
```bash
make inside
```

Check php version
```bash
make php-v
```

Check package version
```bash
make v
```

### Run tests and linters

Run [PHPUnit](https://github.com/sebastianbergmann/phpunit) tests with code coverage
```bash
make test-c 
```

Run [Psalm](https://github.com/vimeo/psalm)
```bash
make psalm
```

Run [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
```bash
make phpcs
```

Or by all-in-one command from the inside of the container

```bash
composer check-all
```

## License

The [php-dto](https://github.com/PetrenkoAnton/php-dto/) library is open-sourced software licensed under the [MIT license](./LICENSE).

## Related projects

- [PetrenkoAnton/php-collection](https://github.com/PetrenkoAnton/php-collection)