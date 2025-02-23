<?php

namespace Looqey\Speca\Attributes;
use Attribute;
use Looqey\Speca\Contracts\PropertyAttribute;
use Looqey\Speca\Contracts\Transformer;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Set implements PropertyAttribute
{
    /**
     * @template T
     * @param ?class-string $of
     * @param class-string<Transformer>|null $parser
     * @param class-string<Transformer>|null $serializer
     */
    public function __construct(protected ?string $of = null, protected ?string $parser = null, protected ?string $serializer = null)
    {
        if ($this->parser && !class_implements($this->parser, Transformer::class)) {
            throw new \InvalidArgumentException("Parser must implement ". Transformer::class . " interface");
        }
        if ($this->serializer && !class_implements($this->serializer, Transformer::class)) {
            throw new \InvalidArgumentException("Serializer must implement ". Transformer::class . " interface");
        }
    }

    public function ofWhat(): ?string {
        return $this->of;
    }

    public function getParser(): ?Transformer {
        return $this->parser ? new $this->parser() : null;
    }

    public function getSerializer(): ?Transformer {
        return $this->serializer ? new $this->serializer() : null;
    }

}