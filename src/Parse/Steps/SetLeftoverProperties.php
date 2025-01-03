<?php

namespace Looqey\Speca\Parse\Steps;

use InvalidArgumentException;
use Looqey\Speca\Infer\Inferer;
use Looqey\Speca\Parse\Context;

class SetLeftoverProperties implements ParseStep
{
    public function process(Context $context): void
    {
        if ($context->instance === null) {
            throw new InvalidArgumentException("Instance was not set");
        }

        foreach ($context->classContext->properties as $propName => $property) {
            if (!isset($context->usedKeys[$propName]) && array_key_exists($propName, $context->inputData)) {
                $rawValue = $context->inputData[$propName];
                $resolvedValue = Inferer::infer($rawValue, $property);
                $context->instance->$propName = $resolvedValue;
            }
        }
    }
}
