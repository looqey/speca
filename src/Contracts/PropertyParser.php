<?php

namespace Looqey\Speca\Contracts;

use Looqey\Speca\Core\Property;

interface PropertyParser
{
    public function parse(mixed $value, Property $property): mixed;
}