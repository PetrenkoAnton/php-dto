<?php

declare(strict_types=1);

namespace Test;

use Dto\Exception\DtoException\InitDtoException\DtoCollectionConstructorException;
use Dto\Helper;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\EmptyClass;
use Tests\Fixtures\InfoArrayable;
use Tests\Fixtures\PersonDtoCollection;
use Tests\Fixtures\ProductDtoCollection;

use function sprintf;

class HelpersTest extends TestCase
{
    /**
     * @group ok
     * @dataProvider dpGetConstructorFirstParameterClassNameFunctionSuccess
     */
    public function testGetConstructorFirstParameterClassNameFunction(object $class, string $expected): void
    {
        $this->assertEquals(Helper::getConstructorFirstParameterClassName($class), $expected);
    }

    public function dpGetConstructorFirstParameterClassNameFunctionSuccess(): array
    {
        return [
            [
                new ProductDtoCollection(),
                'Tests\Fixtures\ProductDto',
            ],
            [
                new PersonDtoCollection(),
                'Tests\Fixtures\PersonDto',
            ],
        ];
    }

    /**
     * @throws DtoCollectionConstructorException
     *
     * @group ok
     * @dataProvider dpGetConstructorFirstParameterClassNameFunctionThrowsDtoCollectionConstructorException
     */
    public function testGetConstructorFirstParameterClassNameFunctionThrowsDtoCollectionConstructorException(
        object $class,
        string $className,
    ): void {
        $this->expectException(DtoCollectionConstructorException::class);
        $this->expectExceptionMessage(
            sprintf('DtoCollection: %s | Err: Invalid constructor declaration', $className),
        );
        $this->expectExceptionCode(107);

        Helper::getConstructorFirstParameterClassName($class);
    }

    public function dpGetConstructorFirstParameterClassNameFunctionThrowsDtoCollectionConstructorException(): array
    {
        return [
            [
                new InfoArrayable(),
                'Tests\Fixtures\InfoArrayable',
            ],
            [
                new EmptyClass(),
                'Tests\Fixtures\EmptyClass',
            ],
        ];
    }
}
