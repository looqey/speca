<?php

namespace Looqey\Speca\Tests\Attributes;

use Looqey\Speca\Attributes\ParseBy;
use Looqey\Speca\Attributes\ParseFrom;
use Looqey\Speca\Contracts\Transformer;
use Looqey\Speca\Core\Property;
use Looqey\Speca\Data;
use PHPUnit\Framework\TestCase;

final class ParsingTest extends TestCase
{
    public function testParseByAttribute(): void {
        $class = new class() extends Data {
            #[ParseBy(ArrayToNameParser::class)]
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

    public function testParseByWillWorkOnConstructorArg(): void {
        $class = new class('it') extends Data {
            #[ParseBy(ArrayToNameParser::class)]
            public string $name;
            public function __construct(
                string $name,
            )
            {
                $this->name = "rough " . $name;
            }
        };
        $data = [
            'name' => 'John',
            'surname' => 'Doe'
        ];
        $it = $class::from([
            'name' => $data
        ]);
        $this->assertSame('rough John Doe', $it->name);
    }

    public function testComplexParsing() {
        $class = new class("it") extends Data {
            public function __construct(
                #[ParseFrom('user_id')]
                #[ParseBy(UserIdToUserNameCaster::class)]
                public string $name,
            )
            {
            }
        };
        $ks = array_keys(UserIdToUserNameCaster::$users);
        foreach ($ks as $k) {
            $it = $class::from(['user_id' => $k]);
            $this->assertSame(UserIdToUserNameCaster::$users[$k], $it->name);
        }
    }
}

class UserIdToUserNameCaster implements Transformer {
    public static array $users =  [
        1 => 'John',
        2 => 'Philipp',
    ];
    public function transform(mixed $value, Property $property): mixed
    {
        return self::$users[$value];
    }

}

class ArrayToNameParser implements Transformer {

    public function transform(mixed $value, Property $property): mixed
    {
        return $value['name'] . ' ' . $value['surname'];
    }
}