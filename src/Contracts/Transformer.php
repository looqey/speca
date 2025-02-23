<?php

namespace Looqey\Speca\Contracts;

use Looqey\Speca\Core\Property;

interface Transformer
{
    public function transform(mixed $value, Property $property): mixed;
}