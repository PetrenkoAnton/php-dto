<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dto\Dto;

/**
 * @method int getActualNumber()
 * @method array getListInfo()
 * @method array getInfoArrayable()
 * @psalm-suppress PossiblyUnusedProperty
 * @psalm-suppress PropertyNotSetInConstructor
 */
class SnakeCaseDto extends Dto
{
    protected int $actualNumber;
    protected array $listInfo;
    protected array $infoArrayable;
}
