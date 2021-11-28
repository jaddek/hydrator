<?php

declare(strict_types=1);

namespace Jaddek\Hydrator\Tests\Stub;

use Jaddek\Hydrator\Collection;

class BookCollection extends Collection
{
    public static function getSupportedItem(): string
    {
        return Book::class;
    }

    public static function getItemsKey(): string
    {
        return 'books';
    }
}
