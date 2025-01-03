<?php

namespace Looqey\Speca\Contracts;


use Looqey\Speca\Core\Property;
use Looqey\Speca\Infer\Result;

interface InferPipe extends Pipe
{
    public function apply(mixed $value, Property $property): Result;

}