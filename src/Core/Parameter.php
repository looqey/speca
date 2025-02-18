<?php

namespace Looqey\Speca\Core;

class Parameter
{
    protected string $name;

    public function __construct(\ReflectionParameter $parameter, protected ?Property $property = null)
    {
        $this->name = $parameter->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }
}
