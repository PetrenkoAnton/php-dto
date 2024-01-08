<?php

declare(strict_types=1);

namespace Test;

use Dto\Exception\EnumNoBackingValueException;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\WithNoBackedEnumDto;

class NoBackedEnumDtoTest extends TestCase
{
    /**
     * @group ok
     */
    public function testGetValueSuccess(): void
    {
        $this->expectException(EnumNoBackingValueException::class);
        $this->expectExceptionMessage('Dto: Tests\Fixtures\WithNoBackedEnumDto | Property: color | Err: No backing value for enum');
        new WithNoBackedEnumDto(['color' => 'red']);
    }
}
