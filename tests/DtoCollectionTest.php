<?php

declare(strict_types=1);

namespace Test;

use Dto\DtoCollection;
use Dto\Exception\SetValueException;
use Dto\Exception\SetValueException\InvalidAddMethodArgument;
use Dto\Exception\SetValueException\InvalidConstructorVariadicParams;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Fixtures\PersonDto;
use Tests\Fixtures\PersonDtoCollection;

class DtoCollectionTest extends TestCase
{
    /**
     * @group ok
     * @throws SetValueException
     */
    public function testAddMethodSuccess(): void
    {
        [$nameAlice, $ageAlice] = ['Alice', 25];
        [$nameBob, $ageBob] = ['Bob', 30];
        [$nameSam, $ageSam] = ['Sam', 35];

        $dtoAlice = new PersonDto(['name' => $nameAlice, 'age' => $ageAlice]);
        $dtoBob = new PersonDto(['name' => $nameBob, 'age' => $ageBob]);
        $dtoSam = new PersonDto(['name' => $nameSam, 'age' => $ageSam]);

        $dtoCollection = new PersonDtoCollection($dtoAlice);
        $dtoCollection->add($dtoBob);
        $dtoCollection->add($dtoSam);

        $this->assertCount(3, $dtoCollection);

        $this->assertEquals($nameAlice, $dtoCollection->first()->getName());
        $this->assertEquals($ageAlice, $dtoCollection->first()->getAge());

        $this->assertEquals($nameAlice, $dtoCollection->getItem(0)->getName());
        $this->assertEquals($ageAlice, $dtoCollection->getItem(0)->getAge());

        $this->assertEquals($nameBob, $dtoCollection->getItem(1)->getName());
        $this->assertEquals($ageBob, $dtoCollection->getItem(1)->getAge());

        $this->assertEquals($nameSam, $dtoCollection->getItem(2)->getName());
        $this->assertEquals($ageSam, $dtoCollection->getItem(2)->getAge());
    }

    /**
     * @group ok
     * @throws SetValueException
     * @dataProvider dp
     */
    public function testAddMethodThrowsInvalidAddMethodArgumentException(mixed $data): void
    {
        $dtoCollection = new PersonDtoCollection();

        $this->expectException(InvalidAddMethodArgument::class);
        $dtoCollection->add($data);
    }

    /**
     * @group +
     * @dataProvider dp
     */
    public function testAddMethodThrowsInvalidConstructorVariadicParamsException(mixed $data): void
    {
        $this->expectException(InvalidConstructorVariadicParams::class);
        new class($data) extends DtoCollection {
            public function __construct(mixed $item)
            {
                parent::__construct();
            }
        };
    }

    public static function dp(): array
    {
        return [
            [null],
            [true],
            [false],
            [-1],
            [0],
            [1],
            [1234],
            [1.0002],
            [''],
            ['random string'],
            [[]],
            [[1]],
            [['a' => 'b']],
            [new StdClass()],
            [(object)['']],
            [(object)[1]],
        ];
    }
}
