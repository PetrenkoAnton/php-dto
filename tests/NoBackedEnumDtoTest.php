<?php

declare(strict_types=1);

namespace Test;

use Dto\Exception\DtoException;
use Dto\Exception\DtoException\InitDtoException;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\WithNoBackedEnumDto;

class NoBackedEnumDtoTest extends TestCase
{
    /**
     * @group ok
     * @throws DtoException
     */
    public function testGetValueSuccess(): void
    {
        $this->expectException(InitDtoException::class);
        $this->expectExceptionMessage('Dto: Tests\Fixtures\WithNoBackedEnumDto | Property: color | Err: No backing value for enum');
        $this->expectExceptionCode(106);
        new WithNoBackedEnumDto(['color' => 'red']);
    }
}
