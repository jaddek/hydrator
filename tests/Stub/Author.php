<?php

declare(strict_types=1);

namespace Jaddek\Hydrator\Tests\Stub;

use Jaddek\Hydrator\Item;

class Author extends Item
{
    public function __construct(
        protected string         $name,
        protected string         $country,
        protected BookCollection $books
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
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @return BookCollection
     */
    public function getBooks(): BookCollection
    {
        return $this->books;
    }
}