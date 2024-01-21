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

| Method | Exception |
|:-------|:----------|
|        |           |

### Exceptions

| Exception | Parent | Message pattern | Code |
|:----------|:-------|:----------------|------|
|           |        |                 |      |

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