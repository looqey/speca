<?php

namespace Looqey\Speca\Infer;


use Looqey\Speca\Core\Pipeline as Base;
use Looqey\Speca\Core\Property;

class Pipeline extends Base
{

    public function execute(mixed $value, Property $property)
    {
        foreach ($this->rules as $rule) {
            $data = $rule->apply($value,$property);
            if ($data->applied || $data->finite) {
                return $data->value;
            }
            $value = $data->value;
        }
        return $value;
    }
}
