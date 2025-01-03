<?php

namespace Looqey\Speca\Parse\Steps;

use InvalidArgumentException;
use Looqey\Speca\Infer\Inferer;
use Looqey\Speca\Parse\Context;

class BuildConstructorArguments implements ParseStep
{
    public function process(Context $context): void
    {
        $constructor = $context->classContext->reflection->getConstructor();
        if ($constructor === null) {
            return;
        }
        foreach ($constructor->getParameters() as $param) {
            $name = $param->getName();
            if (array_key_exists($name, $context->inputData)) {
                $rawValue = $context->inputData[$name];
                if (!isset($context->classContext->properties[$name])) {
                    throw new InvalidArgumentException("Property {$name} is not public or does not exist");
                }
                $property = $context->classContext->properties[$name];
                $context->constructorArgs[$name] = Inferer::infer($rawValue, $property);
                $context->usedKeys[$name] = true;
            } else {
                if ($param->isOptional()) {
                    $context->constructorArgs[$name] = $param->getDefaultValue();
                } else {
                    $className = $context->classContext->reflection->getName();
                    throw new InvalidArgumentException("Argument {$name} is required in {$className}'s constructor");
                }
            }
        }
    }
}
