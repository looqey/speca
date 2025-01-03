<?php

namespace Looqey\Speca\Serialize\Try;

use Looqey\Speca\Core\Context\ObjectContext;
use Looqey\Speca\Core\Property;
use Looqey\Speca\Serialize\Result;
use Looqey\Speca\Types\Lazy;

class TryLazy implements SerializeVariant
{
    protected ObjectContext $oContext;

    public function __construct(ObjectContext $oContext)
    {
        $this->oContext = $oContext;
    }

    public function apply(mixed $value, Property $property): Result
    {
        $pName = $property->getName();
        if ($property->isLazy()) {
            if (!$this->oContext->isIncluded($pName)) {
                return Result::skip();
            }
            if ($value instanceof Lazy) {
                $value = $value->resolve();
            }
        }
        return new Result($pName, $value, true);
    }
}
