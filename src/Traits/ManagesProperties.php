<?php

namespace Looqey\Speca\Traits;

trait ManagesProperties
{
    use HasContexts;

    public function include(string $path): void {
        $this->objectContext()->includes[] = $path;
    }

    public function exclude(string $path): void {
        $this->objectContext()->excludes[] = $path;
    }
}