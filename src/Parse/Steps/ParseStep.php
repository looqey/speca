<?php

namespace Looqey\Speca\Parse\Steps;

use Looqey\Speca\Parse\Context;

interface ParseStep
{
    public function process(Context $context): void;
}
