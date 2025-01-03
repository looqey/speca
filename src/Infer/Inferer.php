<?php

namespace Looqey\Speca\Infer;

use Looqey\Speca\Core\Property;
use Looqey\Speca\Infer\Variants\MaybeData;
use Looqey\Speca\Infer\Variants\MaybeParses;
use Looqey\Speca\Infer\Variants\MaybePrimitive;
use Looqey\Speca\Infer\Variants\MaybeSet;

class Inferer
{
    public static function infer(mixed $value, Property $property): mixed
    {
        $pipeline = new Pipeline(
            new MaybeParses(),
            new MaybeData(),
            new MaybeSet(),
            new MaybePrimitive(),
        );

        return $pipeline->execute($value, $property) ?? $property->default;
    }
}
