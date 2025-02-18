<?php

namespace Looqey\Speca\Traits;

trait ManagesProperties
{
    use HasContexts;

    public function include(string $path, string ...$paths): void
    {
        array_push($this->objectContext()->includes, $path, ...$paths);
    }

    public function exclude(string $path, string ...$paths): void
    {
        array_push($this->objectContext()->excludes, $path, ...$paths);
    }
}
