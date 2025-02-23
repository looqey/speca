<?php

namespace Looqey\Speca\Serialize\Try;

use Looqey\Speca\Attributes\SerializeTo;
use Looqey\Speca\Serialize\PropertyContext;

class TryMapToField implements SerializeVariant
{

    public function apply(PropertyContext $context): PropertyContext
    {
        $property = $context->getProperty();
        $serializeTos = $property->getContractAttributes(SerializeTo::class);
        $k = $context->getKey();

        foreach ($serializeTos as $serializeTo) {
            $k = $serializeTo->getFieldName();
        }
        $context->setKey($k);
        return $context;
    }
}
