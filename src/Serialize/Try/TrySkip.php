<?php

namespace Looqey\Speca\Serialize\Try;

use Looqey\Speca\Core\Context\ObjectContext;
use Looqey\Speca\Serialize\PropertyContext;

class TrySkip implements SerializeVariant
{
    protected ObjectContext $oContext;

    public function __construct(ObjectContext $oContext)
    {
        $this->oContext = $oContext;
    }

    public function apply(PropertyContext $context): PropertyContext
    {
        $pName = $context->getKey();
        if ($this->oContext->isExcluded($pName)) {
            $context->skip();
        }
        return $context;
    }
}
