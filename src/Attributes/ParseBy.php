<?php

namespace Looqey\Speca\Attributes;

use Attribute;
use InvalidArgumentException;
use Looqey\Speca\Contracts\PropertyAttribute;
use Looqey\Speca\Contracts\PropertyParser;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ParseBy implements PropertyAttribute
{
    public function __construct(protected string $parser)
    {
        if (!is_subclass_of($this->parser, PropertyParser::class)) {
            throw new InvalidArgumentException("$this->parser is not a subclass of ".PropertyParser::class);
        }
    }

    /**
     * @return PropertyParser
     */
    public function getParser() {
        return new $this->parser();
    }
}