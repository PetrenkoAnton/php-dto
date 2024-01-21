<?php

declare(strict_types=1);

namespace Tests\Fixtures\Enum;

enum ColorEnum: string
{
    case Red = 'red';
    case Black = 'black';
    case White = 'white';
}
