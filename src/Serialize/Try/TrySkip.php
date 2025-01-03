<?php

namespace Looqey\Speca\Serialize\Try;

use Looqey\Speca\Core\Context\ObjectContext;
use Looqey\Speca\Core\Property;
use Looqey\Speca\Serialize\Result;

class TrySkip implements SerializeVariant
{
    protected ObjectContext $oContext;

    public function __construct(ObjectContext $oContext)
    {
        $this->oContext = $oContext;
    }

    public function apply(mixed $value, Property $property): Result
    {
        $pName = $property->getName();
        if ($this->oContext->isExcluded($pName)) {
            return Result::skip();
        }
        return new Result($pName, $value);
    }
}
