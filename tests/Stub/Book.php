<?php

declare(strict_types=1);

namespace Jaddek\Hydrator\Tests\Stub;

use Jaddek\Hydrator\Item;

class Book extends Item
{
    public function __construct(
        protected string           $name,
        protected int              $pages,
        protected VolumeCollection $volumes,
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

    /**
     * @return int
     */
    public function getPages(): int
    {
        return $this->pages;
    }

    /**
     * @return VolumeCollection
     */
    public function getVolumes(): VolumeCollection
    {
        return $this->volumes;
    }
}