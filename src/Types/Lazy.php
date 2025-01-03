<?php

namespace Looqey\Speca\Types;

class Lazy
{
    public function __construct(
        protected \Closure $callback
    )
    {
    }

    public function resolve() {
        return call_user_func($this->callback);
    }
}