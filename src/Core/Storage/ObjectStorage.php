<?php

namespace Looqey\Speca\Core\Storage;

use WeakMap;

/**
 * @template TContext
 */
class ObjectStorage
{
    /**
     * @var WeakMap<object, TContext>
     */
    private WeakMap $storage;

    public function __construct()
    {
        $this->storage = new WeakMap();
    }

    /**
     *
     * @param object $target
     * @param TContext $context
     */
    public function attach(object $target, mixed $context): mixed
    {
        $this->storage[$target] = $context;
        return $context;
    }

    /**
     *
     * @param object $target
     * @return TContext
     *
     */
    public function get(object $target): mixed
    {

        return $this->storage[$target] ?? null;
    }

    /**
     *
     * @param object $target
     */
    public function detach(object $target): void
    {
        unset($this->storage[$target]);
    }

    /**
     *
     * @param object $target
     * @return bool
     */
    public function has(object $target): bool
    {
        return !!($this->storage[$target] ?? null);
    }
}
