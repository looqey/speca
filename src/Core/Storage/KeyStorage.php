<?php

namespace Looqey\Speca\Core\Storage;

/**
 * @template TContext
 */
class KeyStorage
{
    /**
     * @var array<object|string, TContext>
     */
    private array $storage;

    public function __construct()
    {
        $this->storage = [];
    }

    /**
     *
     * @param string $target
     * @param TContext $context
     * @return TContext
     */
    public function attach(string $target, mixed $context): mixed
    {
        $this->storage[$target] = $context;

        return $context;
    }

    /**
     *
     * @param string $target
     * @return TContext
     */
    public function get(string $target): mixed
    {

        return $this->storage[$target] ?? null;
    }

    /**
     *
     * @param string $target
     */
    public function detach(string $target): void
    {
        unset($this->storage[$target]);
    }

    /**
     *
     * @param string $target
     * @return bool
     */
    public function has(string $target): bool
    {
        return !!($this->storage[$target] ?? null);
    }
}