<?php

namespace Looqey\Speca\Serialize;

use Looqey\Speca\Core\Property;

class PropertyContext
{
    private bool $skip = false;
    private bool $done = false;
    public function __construct(
        private string $key,
        private mixed $value,
        private Property $property
    ) {
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }
    public function getValue(): mixed
    {
        return $this->value;
    }
    public function setKey(string $key): void
    {
        $this->key = $key;
    }
    public function getKey(): string
    {
        return $this->key;
    }

    public function skip()
    {
        $this->skip = true;
    }
    public function isSkipped(): bool
    {
        return $this->skip;
    }

    public function getProperty(): Property
    {
        return $this->property;
    }

    public function setDone()
    {
        $this->done = true;
    }
    public function isDone(): bool
    {
        return $this->done;
    }
}
