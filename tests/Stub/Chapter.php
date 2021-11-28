<?php

declare(strict_types=1);

namespace Jaddek\Hydrator\Tests\Stub;

use Jaddek\Hydrator\Item;

class Chapter extends Item
{
    public function __construct(
        protected string $name,
    )
    {

    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}