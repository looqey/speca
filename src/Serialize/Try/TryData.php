<?php

namespace Looqey\Speca\Serialize\Try;

use Looqey\Speca\Data;
use Looqey\Speca\Serialize\PropertyContext;

class TryData implements SerializeVariant
{
    public function apply(PropertyContext $context): PropertyContext
    {
        $val = $context->getValue();
        if ($val instanceof Data || is_object($val) && method_exists($val, 'toArray')) {
            $context->setValue($val->toArray());
        }
        return $context;
    }
}
