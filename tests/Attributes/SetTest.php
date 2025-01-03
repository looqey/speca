<?php

namespace Looqey\Speca\Tests\Attributes;

use Looqey\Speca\Attributes\Set;
use Looqey\Speca\Contracts\PropertyParser;
use Looqey\Speca\Contracts\PropertySerializer;
use Looqey\Speca\Core\Property;
use Looqey\Speca\Data;
use PHPUnit\Framework\TestCase;

final class SetTest extends TestCase
{
    public function testSetOfAttribute(): void {
        $class = new class() extends Data {
            #[Set(Item::class)]
            public array $items;
            #[Set(of: item::class, parser: ItemCustomParser::class, serializer: ItemCustomSerializer::class)]
            public array $itemsToTestTransformations;
        };

        $values = [
            'items' => [
                [
                    'name' => 'John Doe',
                    'age' => 35
                ],
                [
                    'name' => 'John Doe 2',
                    'age' => 40
                ],
                [
                    'name' => 'John Doe 3',
                    'age' => 45
                ],
                [
                    'name' => 'Old Doe',
                    'age' => 50
                ],
            ],
            'itemsToTestTransformations' => [
                [
                    'name' => 'John Doe',
                    'age' => 35
                ],
                [
                    'name' => 'John Doe 2',
                    'age' => 40
                ],
                [
                    'name' => 'John Doe 3',
                    'age' => 45
                ],
                [
                    'name' => 'Old Doe',
                    'age' => 50
                ],
            ]
        ];

        $instance = $class::from($values);
        $this->assertCount(4, $instance->items);

        $this->assertEquals(40, $instance->items[0]->age);
        $this->assertEquals(55, $instance->items[array_key_last($instance->items)]->age);

        $this->assertCount(4, $instance->itemsToTestTransformations);
        $this->assertEquals(35, $instance->itemsToTestTransformations[0]->age);
        $this->assertEquals(50, $instance->itemsToTestTransformations[array_key_last($instance->itemsToTestTransformations)]->age);
        $output = $instance->toArray();
        foreach ($output["itemsToTestTransformations"] as $each) {
            $this->assertTrue(str_starts_with($each["name"], "rough "));
        }
    }
}

class Item extends Data {
    public string $name;
    public function __construct(
        public int $age
    )
    {
        $this->age = $age + 5;
    }
}

class ItemCustomParser implements PropertyParser {

    public function parse(mixed $value, Property $property): mixed
    {
       $value["age"] -= 5;
       return Item::from($value);
    }
}

class ItemCustomSerializer implements PropertySerializer {
    /**
     * @param array<Item> $value
     * @param Property $property
     * @return mixed
     */
    public function transform(mixed $value, Property $property): mixed
    {
        $value["name"] = "rough ".$value["name"];
        return $value;
    }
}