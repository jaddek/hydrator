<?php

declare(strict_types=1);

namespace Jaddek\Hydrator;

use ReflectionClass;
use ReflectionNamedType;

class Hydrator
{

    public array $levels = [];

    public static function instance(array $data, string $class): Item|Collection
    {
        $reflection = new ReflectionClass($class);

        $instance = new self();
        $instance->associate($reflection);

        $result = $instance->hydrate($data, $reflection);

        if (!$result instanceof $class) {
            throw new HydratorException('Hydration failed');
        }

        return $result;
    }

    private function hydrate(array $data, ReflectionClass $reflection): Item|Collection
    {
        $class = $reflection->getName();

        return match ($this->matchSubclass($reflection)) {
            Collection::class => $this->hydrateCollection($data, $class),
            Item::class => $this->hydrateItem($data, $class),
            default => throw new HydratorException('Unexpected subclass'),
        };
    }

    private function hydrateCollection(array $data, string $class): Collection
    {
        $collection = new $class();

        foreach ($data as $array) {
            foreach ($array as $key => &$value) {
                if (is_object($value)) {
                    $value = (array)$value;
                }

                if (is_array($value) && isset($this->levels[$key])) {
                    $value = $this->hydrateCollection($value, $this->levels[$key]);
                }
            }

            /**
             * @var Collection $class
             */
            $item = $this->hydrateItem($array, $class::getSupportedItem());
            $collection->add($item);
        }

        return $collection;
    }

    private function hydrateItem(array $data, string $class): Item
    {
        foreach ($data as $key => &$value) {
            if (!is_array($value) || !isset($this->levels[$key])) {
                continue;
            }

            $value = $this->hydrateCollection($value, $this->levels[$key]);
        }

        $newArray   = [];
        $reflection = new ReflectionClass($class);

        foreach ($reflection->getConstructor()->getParameters() as $parameter) {
            $parameterValue = $data[$parameter->getName()] ?? null;

            if ($parameterValue === null) {
                $newArray[$parameter->getName()] = $parameterValue;

                continue;
            }

            //@TODO: ReflectionUnionTypes
            if (!$parameter->getType()->isBuiltin() && !$parameterValue instanceof Collection) {
                $newArray[$parameter->getName()] = $this->hydrateItem(
                    $parameterValue,
                    $parameter->getType()->getName(),
                );

                continue;
            }

            $valueType  = $this->getType($parameterValue);
            $paramType  = $parameter->getType()->getName();
            $isNullable = $parameter->getType()->allowsNull();

            if (($valueType === 'NULL' && !$isNullable) && ($valueType !== $paramType)) {
                throw new HydratorException(
                    sprintf(
                        'Different types. An attribute <%s> expecting %s, got %s',
                        $parameter->getName(),
                        $parameter->getType()->getName(),
                        $this->getType($parameterValue),
                    )
                );
            }

            $newArray[$parameter->getName()] = $parameterValue;
        }

        return new $class(...array_values($newArray));
    }

    private function matchSubclass(ReflectionClass $reflection): string
    {
        return match (true) {
            $reflection->isSubclassOf(Collection::class) => Collection::class,
            $reflection->isSubclassOf(Item::class) => Item::class,
            default => throw new HydratorException(sprintf('Unexpected %s subclass', $reflection->getName())),
        };
    }

    private function associate(ReflectionClass $reflection): void
    {
        match ($this->matchSubclass($reflection)) {
            Collection::class => $this->associateCollection($reflection),
            Item::class => $this->associateItem($reflection),
            default => throw new HydratorException('Unexpected subclass'),
        };
    }

    private function associateCollection(ReflectionClass $reflection): void
    {
        /** @var Collection $class */
        $class         = $reflection->getName();
        $supportedItem = $class::getSupportedItem();
        $itemKey       = $class::getItemsKey();

        $this->levels[$itemKey] = $class;

        $this->associate(new ReflectionClass($supportedItem));
    }

    private function associateItem(ReflectionClass $reflection): void
    {
        foreach ($reflection->getProperties() as $property) {
            $type = $property->getType();

            //@TODO: ReflectionUnionTypes
            if (!($type instanceof ReflectionNamedType) || $type->isBuiltin() !== false) {
                continue;
            }

            $item = $type->getName();

            $this->associate(new ReflectionClass($item));
        }
    }

    private function getType(mixed $value): string
    {
        $type = gettype($value);

        return match ($type) {
            'double' => 'float',
            'integer' => 'int',
            default => $type,
        };
    }
}
