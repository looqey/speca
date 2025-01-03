<?php

namespace Looqey\Speca\Core;

use Looqey\Speca\Contracts\Pipe;

abstract class Pipeline
{
    protected array $rules = [];
    public function __construct(Pipe ...$initialRules)
    {
        array_push($this->rules, ...$initialRules);
    }

    public function addRule(Pipe $rule): self
    {
        $this->rules[] = $rule;
        return $this;
    }
}