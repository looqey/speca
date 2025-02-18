<?php

namespace Looqey\Speca\Attributes;

use Attribute;
use Looqey\Speca\Contracts\PropertyAttribute;

#[\Attribute(Attribute::TARGET_PROPERTY)]
class ParseFrom implements PropertyAttribute
{
    protected array $fieldNames = [];
    public function __construct(protected string $fieldName, string ...$fieldNames)
    {
        $this->fieldNames = $fieldNames;
    }

    public function getFieldNames(): array
    {
        return [$this->fieldName, ...$this->fieldNames];
    }
}
