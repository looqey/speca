<?php

namespace Looqey\Speca\Infer\Variants;

use Looqey\Speca\Contracts\InferPipe;
use Looqey\Speca\Contracts\Parseable;
use Looqey\Speca\Core\Property;
use Looqey\Speca\Infer\Result;

class MaybeData implements InferPipe
{

    public function apply(mixed $value, Property $property): Result
    {
        foreach ($property->getTypes() as $type) {
            if (is_subclass_of($type->getName(), Parseable::class)) {
                return new Result(true, ($type->getName())::from($value), true);
            }
        }
        return new Result(false, $value);
    }
}
