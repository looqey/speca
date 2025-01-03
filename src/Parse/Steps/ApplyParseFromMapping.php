<?php

namespace Looqey\Speca\Parse\Steps;

use Looqey\Speca\Parse\Context;
use Looqey\Speca\Attributes\ParseFrom;

class ApplyParseFromMapping implements ParseStep
{
    public function process(Context $context): void
    {
        foreach ($context->classContext->properties as $property) {
            $propName = $property->getName();
            if (array_key_exists($propName, $context->inputData)) {
                continue;
            }

            $parseFromAttrs = $property->getContractAttributes(ParseFrom::class);
            foreach ($parseFromAttrs as $attr) {

                $fieldNames = $attr->getFieldNames();;
                foreach ($fieldNames as $field) {
                    if (array_key_exists($field, $context->inputData)) {
                        $context->inputData[$propName] = $context->inputData[$field];
                        break 2;
                    }

                }
            }
        }
    }
}
