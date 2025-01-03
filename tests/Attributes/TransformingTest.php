<?php

namespace Looqey\Speca\Tests\Attributes;

use Looqey\Speca\Attributes\SerializeBy;
use Looqey\Speca\Contracts\PropertySerializer;
use Looqey\Speca\Core\Property;
use Looqey\Speca\Data;
use PHPUnit\Framework\TestCase;

final class TransformingTest extends TestCase
{
    public function testTransformAttribute(): void {
        $class = new class() extends Data {
            #[SerializeBy(NameToComplexValueSerializer::class)]
            public string $name;
        };

        $instance = $class::from([
            'name' => 'John Doe'
        ]);
        $data = $instance->toArray();
        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('John', $data['name']['name']);
        $this->assertArrayHasKey('surname', $data['name']);
        $this->assertEquals('Doe', $data['name']['surname']);
    }
}

class NameToComplexValueSerializer implements PropertySerializer {

    public function transform(mixed $value, Property $property): mixed
    {
        $values = explode(' ', $value);

        return [
            'name' => array_shift($values),
            'surname' => array_shift($values)
        ];
    }
}