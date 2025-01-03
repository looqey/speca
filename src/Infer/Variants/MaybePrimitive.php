<?php

namespace Looqey\Speca\Infer\Variants;

use Looqey\Speca\Contracts\InferPipe;
use Looqey\Speca\Core\Property;
use Looqey\Speca\Infer\Result;
use ValueError;

class MaybePrimitive implements InferPipe
{

    public function apply(mixed $value, Property $property): Result
    {
        foreach ($property->getTypes() as $type) {
            $casted = $this->castPrimitive($value, $type->name);
            if ($casted)
                return new Result(true, $casted, true);
        }
        return new Result(false, $value, true);
    }

    protected function castPrimitive(mixed $value, string $singleType): mixed
    {
        return match ($singleType) {
            'int', 'integer' => is_numeric($value) ? (int)$value : filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE),
            'float', 'double' => is_numeric($value) ? (float)$value : filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE),
            'bool', 'boolean' => is_bool($value) ? $value : filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'string' => is_scalar($value) ? (string)$value : null,
            'array' => is_array($value) ? $value : null,
            'object' => is_object($value) ? $value : null,
            'mixed' => $value,
            default => $this->castEnum($value, $singleType),
        };
    }

    protected function castEnum(mixed $value, string $enumType): mixed
    {
        if (!enum_exists($enumType)) {
            return null;
        }
        try {
            return $enumType::from($value);
        } catch (ValueError) {
            return null;
        }
    }
}