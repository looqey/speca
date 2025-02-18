<?php


namespace Looqey\Speca\Serialize\Try;

use Looqey\Speca\Contracts\Pipe;
use Looqey\Speca\Core\Property;
use Looqey\Speca\Serialize\Result;

interface SerializeVariant extends Pipe
{
    public function apply(mixed $value, Property $property): Result;

}
