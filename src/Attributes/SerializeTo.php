<?php

namespace Looqey\Speca\Attributes;

use Attribute;
use Looqey\Speca\Contracts\PropertyAttribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class SerializeTo implements PropertyAttribute
{
    public function __construct(protected string $fieldName)
    {
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }
}
