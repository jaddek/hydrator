<?php

declare(strict_types=1);

namespace Jaddek\Hydrator\Tests\Stub;

use Jaddek\Hydrator\Collection;
use Jaddek\Hydrator\Item;

class Volume extends Item
{
    public function __construct(
        protected string             $name,
        protected ?Content           $content,
        protected ?ChapterCollection $chapters,
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
     * @return Collection|null
     */
    public function getChapters(): ?Collection
    {
        return $this->chapters;
    }

    /**
     * @return Content|null
     */
    public function getContent(): ?Content
    {
        return $this->content;
    }
}