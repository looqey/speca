<?php

namespace Looqey\Speca\Traits;

use Looqey\Speca\Core\Context\ClassContext;
use Looqey\Speca\Core\Context\ObjectContext;
use Looqey\Speca\Core\Storage\StorageFactory;

trait HasContexts
{
    public static function classContext(): ClassContext
    {
        $storage = StorageFactory::classContext();
        return $storage->get(static::class)
            ?? $storage->attach(static::class, new ClassContext(static::class));
    }

    public function objectContext(): ObjectContext
    {
        $storage = StorageFactory::objectContext();
        return $storage->get($this)
            ?? $storage->attach($this, new ObjectContext($this));
    }
}
