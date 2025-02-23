<?php

namespace Looqey\Speca\Serialize\Try;

use Looqey\Speca\Attributes\SerializeBy as Attr;
use Looqey\Speca\Serialize\PropertyContext;

class TryTransform implements SerializeVariant
{
    public function apply(PropertyContext $context): PropertyContext
    {
        $property = $context->getProperty();
        $curries = $property->getContractAttributes(Attr::class);
        $value = $context->getValue();
        foreach ($curries as $transformCurry) {
            $value = $transformCurry->getTransformer()->transform($value, $property);
        }
        $context->setValue($value);
        return $context;
    }
}
