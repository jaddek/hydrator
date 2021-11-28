<?php

declare(strict_types=1);

namespace Jaddek\Hydrator;

abstract class Collection extends \ArrayIterator implements \JsonSerializable
{
    protected array $collection;

    public function add(Item $item): void
    {
        $this->collection[] = $item;
    }

    public function getCollection(): array
    {
        return $this->collection;
    }

    public function offsetGet($key): mixed
    {
        return $this->collection[$key] ?? throw new \Exception(sprintf('Undefined key %s', $key));
    }

    public function offsetSet($key, $value)
    {
        if (!$value instanceof Item) {
            throw new \Exception('Invalid type for value. Expected .' . Item::class);
        }

        $this->collection[$key] = $value;
    }

    public function jsonSerialize(): array
    {
        return $this->collection;
    }

    abstract public static function getSupportedItem(): string;

    abstract public static function getItemsKey(): string;
}
