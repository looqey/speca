<?php

namespace Looqey\Speca\Core;

use Looqey\Speca\Types\Lazy;

class PropertyType
{

    public function __construct(
        public string $name,
        public bool $allowsNull,
    )
    {
    }

    public function isLazy(): bool {
        return $this->name === Lazy::class;
    }
}