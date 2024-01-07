<?php

declare(strict_types=1);

namespace Test;

use Dto\Exception\DeclarationException;
use Dto\Exception\DeclarationExceptions\MixedDeclarationException;
use Dto\Exception\DeclarationExceptions\NoTypeDeclarationException;
use Dto\Exception\DeclarationExceptions\NullableDeclarationException;
use Dto\Exception\DeclarationExceptions\ObjectDeclarationException;
use Dto\Exception\InputDataException;
use Dto\Exception\SetValueException;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\Unsupported\MixedDeclarationDto;
use Tests\Fixtures\Unsupported\NoDeclarationDto;
use Tests\Fixtures\Unsupported\NullableDeclarationDto;
use Tests\Fixtures\Unsupported\ObjectDeclarationDto;

class UnsupportedDtoTest extends TestCase
{
    private readonly array $data;

    public function setUp(): void
    {
        $this->data = ['name' => 'Alice'];
    }

    /**
     * @group ok
     * @throws DeclarationException
     * @throws InputDataException
     * @throws SetValueException
     */
    public function testMissingPropertyTypeDeclarationThrowsException(): void
    {
        $this->expectException(NoTypeDeclarationException::class);
        $this->expectExceptionMessage(
            'Dto: Tests\Fixtures\Unsupported\NoDeclarationDto | Property: name | Err: Mission property type declaration'
        );
        new NoDeclarationDto($this->data);
    }

    /**
     * @group ok
     * @throws DeclarationException
     * @throws InputDataException
     * @throws SetValueException
     */
    public function testMixedPropertyTypeDeclarationThrowsException(): void
    {
        $this->expectException(MixedDeclarationException::class);
        $this->expectExceptionMessage(
            'Dto: Tests\Fixtures\Unsupported\MixedDeclarationDto | Property: name | Err: Unsupported mixed property type declaration'
        );
        new MixedDeclarationDto($this->data);
    }

    /**
     * @group ok
     * @throws DeclarationException
     * @throws InputDataException
     * @throws SetValueException
     */
    public function testNullablePropertyTypeDeclarationThrowsException(): void
    {
        $this->expectException(NullableDeclarationException::class);
        $this->expectExceptionMessage(
            'Dto: Tests\Fixtures\Unsupported\NullableDeclarationDto | Property: name | Err: Unsupported nullable property type declaration'
        );
        new NullableDeclarationDto($this->data);
    }

    /**
     * @group ok
     * @throws DeclarationException
     * @throws InputDataException
     * @throws SetValueException
     */
    public function testObjectPropertyTypeDeclarationThrowsException(): void
    {
        $this->expectException(ObjectDeclarationException::class);
        $this->expectExceptionMessage(
            'Dto: Tests\Fixtures\Unsupported\ObjectDeclarationDto | Property: name | Err: Unsupported object property type declaration'
        );
        new ObjectDeclarationDto($this->data);
    }
}
