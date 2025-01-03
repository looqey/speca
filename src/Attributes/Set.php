<?php

namespace Looqey\Speca\Attributes;
use Attribute;
use Looqey\Speca\Contracts\PropertyAttribute;
use Looqey\Speca\Contracts\PropertyParser;
use Looqey\Speca\Contracts\PropertySerializer;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Set implements PropertyAttribute
{
    /**
     * @template T
     * @param string $of
     * @param class-string<PropertyParser>|null $parser
     * @param class-string<PropertySerializer>|null $serializer
     */
    public function __construct(protected string $of, protected ?string $parser = null, protected ?string $serializer = null)
    {
        if ($this->parser && !class_implements($this->parser, PropertyParser::class)) {
            throw new \InvalidArgumentException("Parser must implement ". PropertyParser::class . " interface");
        }
        if ($this->serializer && !class_implements($this->serializer, PropertySerializer::class)) {
            throw new \InvalidArgumentException("Serializer must implement ". PropertySerializer::class . " interface");
        }
    }

    public function ofWhat(): string {
        return $this->of;
    }

    public function getParser(): ?PropertyParser {
        return $this->parser ? new $this->parser() : null;
    }

    public function getSerializer(): ?PropertySerializer {
        return $this->serializer ? new $this->serializer() : null;
    }

}