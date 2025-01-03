<?php declare(strict_types=1);
namespace Looqey\Speca\Tests;


use PHPUnit\Framework\TestCase;
use Looqey\Speca\Data;

final class InstantiationTest extends TestCase
{
    public function testInstantiationWithConstructor(): void
    {
        $class = new class("f", 1) extends Data {
            public string $name;
            public int $age;
            public string $optional;

            public function __construct(string $name, int $age, string $optional = 'default')
            {
                $this->name = "my name is $name";
                $this->age = $age;
                $this->optional = $optional;
            }
        };

        $className = get_class($class);
        $inputData = [
            'name' => 'John Doe',
            'age'  => 35,
        ];

        $instance = $class::from($inputData);

        $this->assertInstanceOf($className, $instance);
        $this->assertEquals('my name is John Doe', $instance->name);
        $this->assertEquals(35, $instance->age);
        $this->assertEquals('default', $instance->optional);
    }

    public function testInstantiationWithoutConstructor(): void {
        $class = new class() extends Data {
            public string $name;
        };

        $className = get_class($class);

        $inputData = [
            'name' => 'John Doe',
        ];

        $instance = $class::from($inputData);

        $this->assertInstanceOf($className, $instance);
        $this->assertEquals('John Doe', $instance->name);
    }

    public function testPartialInstantiation(): void {
        $class = new class("default") extends Data {
            public int $age;
            public string $name;
            public string $optional;
            public function __construct(
                string $name,
                string $optional = 'default'
            )
            {
                $this->name = "my name is $name";
                $this->optional = $optional;
            }
        };

        $className = get_class($class);
        $inputData = [
            'name' => 'John Doe',
            'age'  => 35,
        ];

        $instance = $class::from($inputData);
        $this->assertInstanceOf($className, $instance);
        $this->assertEquals('default', $instance->optional);
    }

    public function testNestedInstantiation(): void {
        $values = [
            'nestedData' => [
                'name' => 'John Doe',
                'age' => 35
            ]
        ];
        $pc = ParentClass::from($values);
        $this->assertTrue(isset($pc->nestedData->name));
        $this->assertEquals("John Doe", $pc->nestedData->name);
    }
}

class ParentClass extends Data {
    public NestedClass $nestedData;
}

class NestedClass extends Data {
    public string $name;
    public string $age;
}
