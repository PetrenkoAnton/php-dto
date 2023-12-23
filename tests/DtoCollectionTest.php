<?php

declare(strict_types=1);

namespace Test;

use PHPUnit\Framework\TestCase;
use Tests\Fixtures\SecondSimpleDataDto;
use Tests\Fixtures\SecondSimpleDataDtoCollection;

class DtoCollectionTest extends TestCase
{
    /**
     * @test
     * @group +
     */
    public function testSuccess(): void
    {
        $stringFirst = 'First String';
        $stringSecond = 'Second String';

        $dtoFirst = new SecondSimpleDataDto(['stringParam' => $stringFirst]);
        $dtoSecond = new SecondSimpleDataDto(['stringParam' => $stringSecond]);

        $dtoCollection = new SecondSimpleDataDtoCollection($dtoFirst, $dtoSecond);

        $this->assertCount(2, $dtoCollection);
        $this->assertEquals($stringFirst, $dtoCollection->first()->getStringParam());
        $this->assertEquals($stringFirst, $dtoCollection->getItems()[0]->getStringParam());
        $this->assertEquals($stringSecond, $dtoCollection->getItems()[1]->getStringParam());
    }
}
