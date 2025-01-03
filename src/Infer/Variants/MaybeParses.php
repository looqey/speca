<?php

namespace Looqey\Speca\Infer\Variants;

use Looqey\Speca\Attributes\ParseBy;
use Looqey\Speca\Contracts\InferPipe;
use Looqey\Speca\Core\Property;
use Looqey\Speca\Infer\Result;

class MaybeParses implements InferPipe
{

    public function apply(mixed $value, Property $property): Result
    {
        $casters = $property->getContractAttributes(ParseBy::class);
        $applied = false;
        foreach ($casters as $caster) {

            $value = $caster->getParser()->parse($value, $property);
            $applied = true;
        }

        return new Result(
            $applied,
            $value,
            $applied
        );
    }
}