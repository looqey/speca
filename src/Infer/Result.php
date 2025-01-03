<?php

namespace Looqey\Speca\Infer;

class Result
{
    public function __construct(
        public bool $applied,
        public mixed $value,
        public bool $finite = false
    )
    {
    }
}