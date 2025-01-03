<?php

namespace Looqey\Speca\Parse\Steps;

use Looqey\Speca\Parse\Context;

class InvokeConstructor implements ParseStep
{
    public function process(Context $context): void
    {
        $reflection = $context->classContext->reflection;
        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            $context->instance = $reflection->newInstanceWithoutConstructor();
            return;
        }

        if ($constructor->getNumberOfParameters() === 0) {
            $context->instance = $reflection->newInstance();
            return;
        }

        $args = [];
        foreach ($constructor->getParameters() as $param) {
            $name = $param->getName();
            $args[] = $context->constructorArgs[$name] ?? null;
        }

        $context->instance = $reflection->newInstanceArgs($args);
    }
}
