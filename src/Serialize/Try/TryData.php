<?php

namespace Looqey\Speca\Serialize\Try;

use Looqey\Speca\Core\Property;
use Looqey\Speca\Data;
use Looqey\Speca\Serialize\Result;

class TryData implements SerializeVariant
{
    public function apply(mixed $value, Property $property): Result
    {
        return new Result(
            $property->getName(),
            $value instanceof Data ? $value->toArray() : $value
        );
    }
}
