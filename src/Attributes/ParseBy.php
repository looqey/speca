<?php

namespace Looqey\Speca\Attributes;

use Attribute;
use InvalidArgumentException;
use Looqey\Speca\Contracts\PropertyAttribute;
use Looqey\Speca\Contracts\Transformer;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ParseBy implements PropertyAttribute
{
    public function __construct(protected string $parser)
    {
        if (!is_subclass_of($this->parser, Transformer::class)) {
            throw new InvalidArgumentException("$this->parser is not a subclass of ".Transformer::class);
        }
    }

    /**
     * @return Transformer
     */
    public function getParser()
    {
        return new $this->parser();
    }
}
