<?php

namespace Looqey\Speca\Serialize;

class Result
{
    public function __construct(
        public string $Key,
        public mixed $value,
        public bool $included = true,
        public bool $finite = false
    ) {
    }

    public static function skip(): self
    {
        return new self('', null, false, true);
    }
}
