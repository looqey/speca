<?php

namespace Looqey\Speca\Tests\Attributes;

use Looqey\Speca\Attributes\ParseBy;
use Looqey\Speca\Attributes\ParseFrom;
use Looqey\Speca\Contracts\PropertyParser;
use Looqey\Speca\Core\Property;
use Looqey\Speca\Data;
use PHPUnit\Framework\TestCase;

final class ParsingTest extends TestCase
{
    public function testParseByAttribute(): void {
        $class = new class() extends Data {
            #[ParseBy(ArrayToNameCaster::class)]
            public string $name;
        };

        $value = [
            'name' => 'John',
            'surname' => 'Doe'
        ];

        $data = $class::from([
           'name' => $value
        ]);

        $this->assertSame('John Doe', $data->name);
    }

    public function testParseFromAttribute(): void {
        $class = new class() extends Data {
            #[ParseFrom("username", "nickname")]
            public string $name;
        };

        $data = [
            'name' => 'John',
            'username' => 'johndoe',
            'nickname' => 'johny',
        ];

        $assert = function (string $k) use (&$data, $class) {
            $o = $class::from($data);
            $this->assertSame($data[$k], $o->name);
            unset($data[$k]);
        };
        $ks = array_keys($data);
        foreach ($ks as $name) {
            $assert($name);
        }
    }
}

class ArrayToNameCaster implements PropertyParser {

    public function parse(mixed $value, Property $property): mixed
    {
        return $value['name'] . ' ' . $value['surname'];
    }
}