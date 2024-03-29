<?php

declare(strict_types=1);

namespace Test;

use Dto\Exception\DtoException\InitDtoException;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\WithNoBackedEnumDto;

class NoBackedEnumDtoTest extends TestCase
{
    /**
     * @throws InitDtoException
     *
     * @group ok
     */
    public function testGetValueSuccess(): void
    {
        $this->expectException(InitDtoException::class);
        // phpcs:ignore
        $this->expectExceptionMessage('Dto: Tests\Fixtures\WithNoBackedEnumDto | Property: color | Err: No backing value for enum');
        $this->expectExceptionCode(105);
        new WithNoBackedEnumDto(['color' => 'red']);
    }
}
