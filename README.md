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

[Installation](#installation) | [Functionality](#functionality) | [Usage](#usage) | [For developers](#for-developers) | [License](#license)

## Installation

### Requirements

- PHP8.1 or higher

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

| Method                       | Exception    |
|:-----------------------------|:-------------|
| __construct(Dto ...$items)   | DtoException |
| add(Collectable $item): void | DtoException |

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
| 107  | DtoCollection: %s &#124 Err: Invalid constructor declaration                                                  | DtoCollectionConstructorException | InitDtoException   |
| 201  | DtoCollection: %s &#124; Expected Dto: %s &#124; Given Dto: %s                                                | AddDtoException                   | SetupDtoException  |
| 202  | Dto: %s &#124; Property: %s &#124; Err: No data                                                               | InputDataException                | SetupDtoException  |
| 203  | Dto: %s &#124; Property: %s &#124; Expected type: %s &#124; Given type: %s &#124; Value: %s                   | SetValueException                 | SetupDtoException  |
| 204  | Dto: %s &#124; Property: %s &#124; Enum: %s &#124; Expected values: %s &#124; Given type: %s &#124; Value: %s | SetValueEnumException             | SetupDtoException  |
| 301  | Dto: %s &#124; %s                                                                                             | GetValueException                 | HandleDtoException |

## Usage

...

## For developers

### Requirements

Utils:
- make
- [docker-compose](https://docs.docker.com/compose/gettingstarted)

### Setup

Initialize:

```bash
make init # Create ./docker/.env 
```

Build container with the different php version:

```bash
make up81 # php8.1
make up82 # php8.2
make up83 # php8.3
```

Also you need to run this command before build container with another php version:

```bash
make down # Remove network and container
```

Other commands:

```bash
make inside # Go inside of the container
make php-v # Check php version
make v # Check package version
```

### Run tests and linters

Using `make` util:

```bash
make test-c # Run tests with code coverage
make psalm # Run Psalm
make phpcs # Run PHP_CSFixer
```

Or from the inside of the container: 

```bash
composer check-all
```

## License

The [php-dto](https://github.com/PetrenkoAnton/php-dto/) library is open-sourced software licensed under the 
[MIT license](https://opensource.org/licenses/MIT).

## Related projects

- [PetrenkoAnton/php-collection](https://github.com/PetrenkoAnton/php-collection)