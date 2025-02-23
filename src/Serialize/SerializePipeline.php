<?php

namespace Looqey\Speca\Serialize;

use Looqey\Speca\Core\Context\ObjectContext;
use Looqey\Speca\Core\Pipeline;
use Looqey\Speca\Data;
use Looqey\Speca\Serialize\Try\SerializeVariant;

/**
 * @property array<SerializeVariant> $rules
 */
class SerializePipeline extends Pipeline
{
    /**
     * @var ObjectContext
     */
    private ObjectContext $context;


    public function __construct(ObjectContext $context, ...$initialRules)
    {
        parent::__construct(...$initialRules);
        $this->context = $context;
    }

    /**
     * @param Data $instance
     * @return array
     */
    public function execute(Data $instance): array
    {
        $properties = $instance::classContext()->properties;
        $output = [];

        foreach ($properties as $property) {
            $key = $property->getName();
            $context = new PropertyContext(
                $key,
                $instance->$key,
                $property
            );
            $context = $this->tryComplexValue($context);

            foreach ($this->rules as $rule) {
                $context = $rule->apply($context);
                if ($context->isSkipped()) {
                    continue 2;
                }
                if ($context->isDone()) {
                    break;
                }
            }

            $output[$context->getKey()] = $context->getValue();
        }

        return $output;
    }

    private function tryComplexValue(PropertyContext $context): PropertyContext
    {
        $val = $context->getValue();
        $key = $context->getKey();
        if (is_object($val) && method_exists($val, 'toArray')) {
            $nestedContext = $this->context->filterForNested($key);
            $serializer = new Serializer();
            $context->setValue($serializer->serialize($val, $nestedContext));
        }
        if (is_iterable($val)) {
            foreach ($val as $k => $v) {
                if (is_object($v) && method_exists($v, 'toArray')) {
                    $nestedContext = $this->context->filterForNested($key);
                    $serializer = new Serializer();
                    $val[$k] = $serializer->serialize($v, $nestedContext);
                }
            }
            $context->setValue($val);
        }
        return $context;
    }
}
