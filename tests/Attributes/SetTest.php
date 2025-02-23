<?php

namespace Looqey\Speca\Tests\Attributes;

use Looqey\Speca\Attributes\Set;
use Looqey\Speca\Contracts\Transformer;
use Looqey\Speca\Core\Property;
use Looqey\Speca\Data;
use PHPUnit\Framework\TestCase;

final class SetTest extends TestCase
{
    public function testSetOfAttribute(): void
    {
        $class = new class() extends Data {
            #[Set(Item::class)]
            public array $items;
            #[Set(serializer: ItemCustomSerializer::class)]
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
        $this->assertEquals(35, $instance->itemsToTestTransformations[0]['age']);
        $this->assertEquals(50, $instance->itemsToTestTransformations[array_key_last($instance->itemsToTestTransformations)]['age']);
        $output = $instance->toArray();
        foreach ($output["itemsToTestTransformations"] as $each) {
            $this->assertTrue(str_starts_with($each["name"], "rough "));
        }
    }

    public function testSetWithSerializerParserOnly()
    {
        $class = new class() extends Data {
            #[Set(parser: ItemCustomParser::class, serializer: ItemCustomSerializer::class)]
            public array $items;
        };

        $values = [
            'items' => [
                [
                    'name' => 'John Doe',
                    'age' => 35
                ],
            ]
        ];
        $instance = $class::from($values);

        $this->assertEquals( 35, $instance->items[0]->age);

        $arr = $instance->toArray();
        $this->assertStringStartsWith("rough", $arr["items"][0]["name"]);
    }

    public function testSetWithSerializerOnly()
    {
        $class = new class() extends Data {
            #[Set(serializer: ItemCustomSerializer::class)]
            public array $items;
        };

        $values = [
            'items' => [
                [
                    'name' => 'John Doe',
                    'age' => 351
                ],
            ]
        ];
        $instance = $class::from($values);
        $this->assertEquals( 351, $instance->items[0]["age"]);

        $arr = $instance->toArray();
        $this->assertStringStartsWith("rough", $arr["items"][0]["name"]);
    }
}

class Item extends Data
{
    public string $name;

    public function __construct(
        public int $age
    )
    {
        $this->age = $age + 5;
    }
}

class ItemCustomParser implements Transformer
{

    public function transform(mixed $value, Property $property): mixed
    {
        $value["age"] -= 5;
        return Item::from($value);
    }
}

class ItemCustomSerializer implements Transformer
{
    /**
     * @param array<Item> $value
     * @param Property $property
     * @return mixed
     */
    public function transform(mixed $value, Property $property): mixed
    {
        $value["name"] = "rough " . $value["name"];
        return $value;
    }
}