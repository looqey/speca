<?php

namespace Looqey\Speca\Attributes;

use Attribute;
use InvalidArgumentException;
use Looqey\Speca\Contracts\PropertyAttribute;
use Looqey\Speca\Contracts\PropertySerializer;

#[Attribute(Attribute::TARGET_PROPERTY)]
class SerializeBy implements PropertyAttribute
{

    public function __construct(protected string $transformer)
    {
        if (!is_subclass_of($this->transformer, PropertySerializer::class)) {
            throw new InvalidArgumentException("$transformer is not a subclass of ".PropertySerializer::class);
        }
    }

    /**
     * @return PropertySerializer
     */
    public function getTransformer()
    {
        return new $this->transformer();
    }

}
