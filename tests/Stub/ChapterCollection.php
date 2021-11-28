<?php

declare(strict_types=1);

namespace Jaddek\Hydrator\Tests\Stub;

use Jaddek\Hydrator\Collection;

class ChapterCollection extends Collection
{
    public static function getSupportedItem(): string
    {
        return Chapter::class;
    }

    public static function getItemsKey(): string
    {
        return 'chapters';
    }
}
