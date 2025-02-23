<?php

namespace Looqey\Speca\Serialize\Try;

use Looqey\Speca\Core\Context\ObjectContext;
use Looqey\Speca\Serialize\PropertyContext;
use Looqey\Speca\Types\Lazy;

class TryLazy implements SerializeVariant
{
    protected ObjectContext $oContext;

    public function __construct(ObjectContext $oContext)
    {
        $this->oContext = $oContext;
    }

    public function apply(PropertyContext $context): PropertyContext
    {
        $prop = $context->getProperty();
        if ($prop->isLazy()) {
            if (!$this->oContext->isIncluded($context->getKey())) {
                $context->skip();
                return $context;
            }
            $val = $context->getValue();
            if ($val instanceof Lazy) {
                $context->setValue($val->resolve());
            }
        }
        return $context;
    }
}
