<?php

namespace Looqey\Speca\Contracts;

interface Parseable
{
    public static function from(mixed $any): mixed;
}
