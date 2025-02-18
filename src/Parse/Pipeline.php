<?php

namespace Looqey\Speca\Parse;

use Looqey\Speca\Parse\Steps\ParseStep;

class Pipeline
{
    /**
     * @var ParseStep[]
     */
    private array $steps = [];

    /**
     * @param ParseStep[] $steps
     */
    public function __construct(array $steps)
    {
        $this->steps = $steps;
    }

    public function process(Context $context): void
    {
        foreach ($this->steps as $step) {
            $step->process($context);
        }
    }
}
