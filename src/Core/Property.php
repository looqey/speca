<?php

namespace Looqey\Speca\Core;

use Looqey\Speca\Contracts\PropertyAttribute;

/**
 * @property array<PropertyType> $types
 */
class Property
{
    public string $name;
    public array $types = [];
    public array $attributes = [];
    public mixed $default;

    public function __construct(
        private \ReflectionProperty $property
    ) {
        $this->name = $this->property->getName();
        $this->default = $this->property->getDefaultValue();
        $this->initializeTypes();
        $this->initializeAttributes();
    }

    private function initializeTypes(): void
    {
        $type = $this->property->getType();
        $this->types = $type instanceof \ReflectionUnionType
            ? array_map(fn($t) => new PropertyType($t->getName(), $t->allowsNull()), $type->getTypes())
            : ($type ? [new PropertyType($type->getName(), $type->allowsNull())] : []);
    }

    private function initializeAttributes(): void
    {
        $this->attributes = array_map(
            fn(\ReflectionAttribute $attr) => $attr->newInstance(),
            array_filter(
                $this->property->getAttributes(),
                fn(\ReflectionAttribute $attr) => is_subclass_of($attr->getName(), PropertyAttribute::class)
            )
        );
    }

    public function isLazy(): bool
    {
        return !!count(array_filter($this->types, fn(PropertyType $type) => $type->isLazy()));
    }

    /**
     * @template T
     * @param class-string<T> $contract
     * @return array<T>
     */
    public function getContractAttributes(string $contract): array
    {
        return array_filter(
            $this->attributes,
            fn($attr) => $attr instanceof $contract
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<PropertyType>
     */
    public function getTypes(): array
    {
        return $this->types;
    }
}
