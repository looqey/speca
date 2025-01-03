<?php

namespace Looqey\Speca\Parse;

use Looqey\Speca\Core\Context\ClassContext;
use Looqey\Speca\Data;


class Context
{
    public array $inputData;

    public array $constructorArgs = [];

    public array $usedKeys = [];

    public ClassContext $classContext;

    public ?object $instance = null;

    public function __construct(string $targetClass, array $inputData)
    {
        $this->inputData = $inputData;
        assert(is_subclass_of($targetClass, Data::class));
        $this->classContext = $targetClass::classContext();
    }
}
