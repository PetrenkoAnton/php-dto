<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Collection\Arrayable;

class InfoArrayable implements Arrayable
{
    public function toArray(): array
    {
        return ['key' => 'value'];
    }
}
