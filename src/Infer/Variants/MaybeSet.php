<?php

namespace Looqey\Speca\Infer\Variants;

use InvalidArgumentException;
use Looqey\Speca\Attributes\Set;
use Looqey\Speca\Contracts\InferPipe;
use Looqey\Speca\Contracts\Parseable;
use Looqey\Speca\Core\Property;
use Looqey\Speca\Infer\Result;

class MaybeSet implements InferPipe
{
    public function apply(mixed $value, Property $property): Result
    {
        $attributes = $property->getContractAttributes(Set::class);
        if (!empty($attributes)) {
            $setOf = $attributes[0];
            $propertyName = $property->getName();
            if (!is_iterable($value)) {
                throw new InvalidArgumentException("Property {$propertyName} has SetOf arrtibute, but provided value is not iterable.");
            }
            $className = $setOf->ofWhat();
            $parser = $setOf->getParser();
            if (!is_subclass_of($className, Parseable::class)
                && !$parser) {
                throw new \Error("Could not instantiate class that is not data yet.");
            }
            $converted = [];

            $via = fn ($v) => $parser ? $parser->parse($v, $property) : $className::from($v);

            foreach ($value as $key => $item) {
                $converted[$key] = $via($item);
            }
            return new Result(true, $converted, true);
        }
        return new Result(false, $value);
    }
}
