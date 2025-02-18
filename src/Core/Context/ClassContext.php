<?php

namespace Looqey\Speca\Core\Context;

use Looqey\Speca\Core\Parameter;
use Looqey\Speca\Core\Property;

/**
 * @property array<string, Property> $properties
 */
class ClassContext
{
    public array $properties = [];
    public \ReflectionClass $reflection;
    private ?array $constructorParameters = null;
    public function __construct(string $target)
    {
        $this->reflection = new \ReflectionClass($target);
        $this->initializeProperties();
    }

    private function initializeProperties(): void
    {
        foreach ($this->reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $this->properties[$property->getName()] = new Property($property);
        }
    }

    /**
     * @return array<string, Property>
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @return array<Parameter>
     */
    public function getConstructorParameters(): array
    {
        return $this->constructorParameters
            ?? $this->constructorParameters = array_map(
                fn (\ReflectionParameter $it) => new Parameter($it, $this->properties[$it->getName()] ?? null),
                $this->reflection->getConstructor()?->getParameters() ?? []
            );
    }
}
