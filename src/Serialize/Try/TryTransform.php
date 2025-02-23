<?php

namespace Looqey\Speca\Serialize\Try;

use Looqey\Speca\Attributes\SerializeBy as Attr;
use Looqey\Speca\Serialize\PropertyContext;

class TryTransform implements SerializeVariant
{
    public function apply(PropertyContext $context): PropertyContext
    {
        $property = $context->getProperty();
        $transformers = $property->getContractAttributes(Attr::class);
        $value = $context->getValue();
        foreach ($transformers as $transformer) {
            $value = $transformer->getTransformer()->serialize($value, $property);
        }
        $context->setValue($value);
        return $context;
    }
}
