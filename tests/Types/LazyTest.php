<?php

namespace Looqey\Speca\Tests\Types;

use Looqey\Speca\Data;
use Looqey\Speca\Types\Lazy;
use PHPUnit\Framework\TestCase;

final class LazyTest extends TestCase
{
    public function testNotIncludedLazyPropertyDoesNotIncludes(): void {
        $class = new class() extends Data {
            public Lazy|string $name;
        };

        $values = [
            'name' => new Lazy(fn () => 'John Doe')
        ];
        $instance = $class::from($values);
        $data = $instance->toArray();

        $instanceWithInclude = $class::from($values);
        $instanceWithInclude->include('name');
        $dataWithInclude = $instanceWithInclude->toArray();

        $this->assertArrayNotHasKey('name', $data);
        $this->assertArrayHasKey('name', $dataWithInclude);
        $this->assertEquals('John Doe', $dataWithInclude['name']);
    }

    public function testIncludeNestedProperty(): void {

        $parentClass = new class() extends Data {
            public NestedClass $nestedData;

        };

        $nestedValues = [
            'name' => new Lazy(fn () => 'John Doe')
        ];
        $nestedInstance = NestedClass::from($nestedValues);
        $parentInstance = $parentClass::from([
            'nestedData' => $nestedInstance
        ]);
        $parentInstance->include('nestedData.name');
        $result = $parentInstance->toArray();
        $this->assertArrayHasKey('nestedData', $result);
        $this->assertArrayHasKey('name', $result["nestedData"]);
        $this->assertEquals("John Doe", $result["nestedData"]["name"]);
    }

    public function testExcludeProperty() {
        $parentClass = new class('it') extends Data {
            public NestedClass $nestedData;
            public function __construct(
                public string $excludedDefault = 'default'
            )
            {
            }
        };

        $obj = $parentClass::from(['nestedData' => ['name' => 'test']]);
        $obj->exclude('nestedData.defaultProp');
        $obj->exclude('excludedDefault');
        $result = $obj->toArray();
        $this->assertArrayNotHasKey('defaultProp', $result["nestedData"]);
        $this->assertArrayNotHasKey('excludedDefault', $result);
    }

}
class NestedClass extends Data {
    public Lazy|string $name;
    public string $defaultProp = 'default';
}