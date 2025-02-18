<?php

namespace Looqey\Speca\Contracts;

use Looqey\Speca\Core\Property;

interface PropertySerializer
{

    public function serialize(mixed $value, Property $property): mixed;
}
