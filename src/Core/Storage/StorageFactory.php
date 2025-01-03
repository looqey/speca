<?php

namespace Looqey\Speca\Core\Storage;

use Looqey\Speca\Core\Context\ObjectContext;
use Looqey\Speca\Core\Property;

class StorageFactory
{
    private static ?ObjectStorage $objectStorage = null;
    private static ?KeyStorage $classStorage = null;

    /**
     * @return ObjectStorage<ObjectContext>
     */
    public static function objectContext(): ObjectStorage
    {
        if (self::$objectStorage === null) {
            self::$objectStorage = new ObjectStorage();
        }

        return self::$objectStorage;
    }
    /**
     * @return KeyStorage<string, Property>
     */
    public static function classContext(): KeyStorage
    {
        if (self::$classStorage === null) {
            self::$classStorage = new KeyStorage();
        }
        return self::$classStorage;
    }
}