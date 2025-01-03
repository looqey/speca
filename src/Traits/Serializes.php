<?php
namespace Looqey\Speca\Traits;

use Looqey\Speca\Attributes\SerializeBy;
use Looqey\Speca\Core\Property;
use Looqey\Speca\Serialize\Serializer;

trait Serializes
{
    public function toArray(): array
    {
        return (new Serializer())->serialize($this);
    }

    public function toJson(int $options = 0): string
    {
        return (new Serializer())->jsonize($this, $options);
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function __serialize(): array
    {
        return $this->toArray();
    }

    public function __unserialize(array $data): void
    {
        $instance = static::from($data);

        if ($instance) {
            $context = static::classContext();
            foreach ($context->properties as $prop) {
                $pName = $prop->getName();
                $this->$pName = $instance->$pName;
            }
        }
    }

    private function applyTransformers(mixed $value, Property $property): mixed
    {
        $transformers = $property->getContractAttributes(SerializeBy::class);

        foreach ($transformers as $transformer) {
            $value = $transformer->getTransformer()->transform($value, $property);
        }

        return $value;
    }
}