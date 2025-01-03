<?php

namespace Looqey\Speca\Serialize;

use Looqey\Speca\Core\Context\ObjectContext;
use Looqey\Speca\Core\Pipeline;
use Looqey\Speca\Data;

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
            $propertyValue = $instance->$key;
            if (is_object($propertyValue) && method_exists($propertyValue, 'toArray')) {
                $nestedContext = $this->context->filterForNested($key);
                $serializer = new Serializer();
                $propertyValue = $serializer->serialize($propertyValue, $nestedContext);
            }
            if (is_iterable($propertyValue)) {
                foreach ($propertyValue as $k => $v) {
                    if (is_object($v) && method_exists($v, 'toArray')) {
                        $nestedContext = $this->context->filterForNested($key);
                        $serializer = new Serializer();
                        $propertyValue[$k] = $serializer->serialize($v, $nestedContext);
                    }
                }
            }

            foreach ($this->rules as $rule) {
                $response = $rule->apply($propertyValue, $property);
                if (!$response->included) {
                    continue 2;
                }
                $propertyValue = $response->value;
                $key = $response->Key;
                if ($response->finite) {
                    break;
                }
            }

            $output[$key] = $propertyValue;
        }

        return $output;
    }
}
