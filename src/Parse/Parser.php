<?php

namespace Looqey\Speca\Parse;

use InvalidArgumentException;
use Looqey\Speca\Parse\Steps\ApplyParseFromMapping;
use Looqey\Speca\Parse\Steps\BuildConstructorArguments;
use Looqey\Speca\Parse\Steps\InvokeConstructor;
use Looqey\Speca\Parse\Steps\SetLeftoverProperties;

class Parser
{
    public static function instantiate(string $class, array $data): object
    {
        if (!class_exists($class)) {
            throw new InvalidArgumentException("Class {$class} does not exist");
        }

        $context = new Context($class, $data);

        $pipeline = new Pipeline([
            new ApplyParseFromMapping(),
            new BuildConstructorArguments(),
            new InvokeConstructor(),
            new SetLeftoverProperties(),
        ]);

        $pipeline->process($context);

        return $context->instance
            ?? $context->classContext->reflection->newInstanceWithoutConstructor();
    }
}
