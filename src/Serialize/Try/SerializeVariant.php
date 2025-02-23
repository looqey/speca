<?php


namespace Looqey\Speca\Serialize\Try;

use Looqey\Speca\Contracts\Pipe;
use Looqey\Speca\Serialize\PropertyContext;

interface SerializeVariant extends Pipe
{
    public function apply(PropertyContext $context): PropertyContext;

}
