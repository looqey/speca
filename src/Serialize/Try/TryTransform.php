<?php

namespace Looqey\Speca\Serialize\Try;

use Looqey\Speca\Attributes\SerializeBy as Attr;
use Looqey\Speca\Core\Property;
use Looqey\Speca\Serialize\Result;

class TryTransform implements SerializeVariant
{
    public function apply(mixed $value, Property $property): Result
    {
        $transformers = $property->getContractAttributes(Attr::class);

        foreach ($transformers as $transformer) {
            $value = $transformer->getTransformer()->transform($value, $property);
        }
        return new Result($property->getName(), $value, true, !!count($transformers));
    }
}
