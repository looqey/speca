<?php

namespace Looqey\Speca\Traits;

use Looqey\Speca\Parse\Parser;

/**
 * @template T of static
 */
trait Parses
{
    /**
     * @param mixed $any
     * @return T
     *
     */
    public static function from(mixed $any): mixed
    {
        return Parser::instantiate(static::class, (array)$any);
    }
}
