<?php

namespace Looqey\Speca\Tests\Attributes;

use Looqey\Speca\Attributes\SerializeBy;
use Looqey\Speca\Attributes\SerializeTo;
use Looqey\Speca\Contracts\PropertySerializer;
use Looqey\Speca\Core\Property;
use Looqey\Speca\Data;
use PHPUnit\Framework\TestCase;

class SerializingTest extends TestCase
{
    public function testSerializeAttributes(): void
    {
        $class = new class() extends Data {
            #[SerializeBy(NameToComplexValueSerializer::class)]
            public string $name;
            #[SerializeTo('user_id')]
            public int $id;
        };

        $instance = $class::from([
            'name' => 'John Doe',
            'id' => 500
        ]);
        $data = $instance->toArray();

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('John', $data['name']['name']);
        $this->assertArrayHasKey('surname', $data['name']);
        $this->assertEquals('Doe', $data['name']['surname']);
        $this->assertArrayHasKey('user_id', $data);
        $this->assertArrayNotHasKey('id', $data);

    }
}

class NameToComplexValueSerializer implements PropertySerializer
{

    public function serialize(mixed $value, Property $property): mixed
    {
        $values = explode(' ', $value);

        return [
            'name' => array_shift($values),
            'surname' => array_shift($values)
        ];
    }
}