<?php

declare(strict_types=1);

namespace Jaddek\Hydrator\Tests\Stub;

use Jaddek\Hydrator\Collection;

class VolumeCollection extends Collection
{
    public static function getSupportedItem(): string
    {
        return Volume::class;
    }

    public static function getItemsKey(): string
    {
        return 'volumes';
    }
}
