<?php

namespace Looqey\Speca\Serialize\Try;

use Looqey\Speca\Attributes\Set;
use Looqey\Speca\Core\Property;
use Looqey\Speca\Data;
use Looqey\Speca\Serialize\Result;

class TrySet implements SerializeVariant
{
    public function apply(mixed $value, Property $property): Result
    {
        $set = $property->getContractAttributes(Set::class)[0] ?? null;
        if ($set) {
            $pName = $property->getName();
            if (!is_iterable($value)) {
                throw new \InvalidArgumentException("Property $pName declared as set of instances. Given value is not iterable.");
            }
            $cn = $set->ofWhat();
            $serializer = $set->getSerializer();

            if (!is_subclass_of($cn, Data::class)
            && !$serializer) {
                throw new \Error('Not implemented');
            }

            $via = fn ($v) => $serializer ? $serializer->transform($v, $property) : $cn::from($v);
            foreach ($value as $k => $item) {
                $value[$k] = $via($item);
            }
            return new Result($pName, $value, true, true);
        }

        return new Result($property->getName(), $value);
    }
}
