<?php

declare(strict_types=1);

namespace Test;

use Dto\Exception\DtoException;
use Dto\Exception\DtoException\InitDtoException;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\Unsupported\MixedDeclarationDto;
use Tests\Fixtures\Unsupported\NoDeclarationDto;
use Tests\Fixtures\Unsupported\NullableDeclarationDto;
use Tests\Fixtures\Unsupported\ObjectDeclarationDto;
use Tests\Fixtures\Unsupported\RandomClass;
use Tests\Fixtures\Unsupported\RandomClassDeclarationDto;

class UnsupportedDtoTest extends TestCase
{
    private array $data;

    public function setUp(): void
    {
        $this->data = ['name' => 'Alice'];
    }

    /**
     * @throws DtoException
     *
     * @group ok
     */
    public function testMissingPropertyTypeDeclarationThrowsException(): void
    {
        $this->expectException(InitDtoException::class);
        $this->expectExceptionMessage(
            // phpcs:ignore
            'Dto: Tests\Fixtures\Unsupported\NoDeclarationDto | Property: name | Err: Mission property type declaration',
        );
        $this->expectExceptionCode(101);
        new NoDeclarationDto($this->data);
    }

    /**
     * @throws DtoException
     *
     * @group ok
     */
    public function testMixedPropertyTypeDeclarationThrowsException(): void
    {
        $this->expectException(InitDtoException::class);
        $this->expectExceptionMessage(
            // phpcs:ignore
            'Dto: Tests\Fixtures\Unsupported\MixedDeclarationDto | Property: name | Err: Unsupported mixed property type declaration',
        );
        $this->expectExceptionCode(103);
        new MixedDeclarationDto($this->data);
    }

    /**
     * @throws DtoException
     *
     * @group ok
     */
    public function testNullablePropertyTypeDeclarationThrowsException(): void
    {
        $this->expectException(InitDtoException::class);
        $this->expectExceptionMessage(
            // phpcs:ignore
            'Dto: Tests\Fixtures\Unsupported\NullableDeclarationDto | Property: name | Err: Unsupported nullable property type declaration',
        );
        $this->expectExceptionCode(102);
        new NullableDeclarationDto($this->data);
    }

    /**
     * @throws DtoException
     *
     * @group ok
     */
    public function testObjectPropertyTypeDeclarationThrowsException(): void
    {
        $this->expectException(InitDtoException::class);
        $this->expectExceptionMessage(
            // phpcs:ignore
            'Dto: Tests\Fixtures\Unsupported\ObjectDeclarationDto | Property: name | Err: Unsupported object property type declaration',
        );
        $this->expectExceptionCode(104);
        new ObjectDeclarationDto($this->data);
    }

    /**
     * @throws DtoException
     *
     * @group ok
     */
    public function testRandomClassPropertyTypeDeclarationThrowsException(): void
    {
        $this->expectException(InitDtoException::class);
        $this->expectExceptionMessage(
            // phpcs:ignore
            'Dto: Tests\Fixtures\Unsupported\RandomClassDeclarationDto | Property: name | Err: Class must implement DtoInterface',
        );
        $this->expectExceptionCode(105);
        new RandomClassDeclarationDto(['name' => new RandomClass()]);
    }
}
