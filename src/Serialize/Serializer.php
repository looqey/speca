<?php

namespace Looqey\Speca\Serialize;

use Looqey\Speca\Core\Context\ObjectContext;
use Looqey\Speca\Data;
use Looqey\Speca\Serialize\Try\TryData;
use Looqey\Speca\Serialize\Try\TryMapToField;
use Looqey\Speca\Serialize\Try\TrySet;
use Looqey\Speca\Serialize\Try\TryLazy;
use Looqey\Speca\Serialize\Try\TrySkip;
use Looqey\Speca\Serialize\Try\TryTransform;

class Serializer
{
    public function serialize(Data $data, ?ObjectContext $context = null): array
    {
        if ($context === null) {
            $context = $data->objectContext();
        }

        $pipeline = new SerializePipeline(
            $context,
            new TrySkip($context),
            new TryTransform(),
            new TryLazy($context),
            new TryData(),
            new TryMapToField(),
            new TrySet()
        );
        return $pipeline->execute($data);
    }


    public function jsonize(Data $data, int $options = 0, ?ObjectContext $context = null): string
    {
        return json_encode($this->serialize($data, $context), $options);
    }
}
