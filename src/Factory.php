<?php declare(strict_types=1);

namespace Monarch;

/**
 * Manages instances of singleton classes.
 */
class Factory
{
    private static array $instances = [];

    /**
     * Retrieves an instance of the given class.
     * If any additional arguments are passed, they will be used
     * to create a fingerprint that helps identify a unique instance
     * is needed.
     *
     * For example, if you need to create multiple instances of the same
     * class, you can pass a unique string as the second argument, and a
     * new instance will be created the first time that string is passed.
     * If you request the class again with the same string, the same instance
     * will be returned.
     */
    public static function get(string $class, ...$args): object
    {
        $fingerprint = md5($class . serialize($args));

        if (!isset(self::$instances[$fingerprint])) {
            self::$instances[$fingerprint] = new $class(...$args);
        }

        return self::$instances[$fingerprint];
    }

    /**
     * Sets the instance of the given class.
     * This is useful during testing where you want to place
     * a mock object in place of a real one.
     */
    public static function set(string $class, object $instance, ...$args): void
    {
        $fingerprint = md5($class . serialize($args));

        self::$instances[$fingerprint] = $instance;
    }

    /**
     * Resets the instances of the given class.
     */
    public static function resetClass(string $class, ...$args): void
    {
        $fingerprint = md5($class . serialize($args));

        if (isset(self::$instances[$fingerprint])) {
            unset(self::$instances[$fingerprint]);
        }
    }

    /**
     * Resets all instances.
     */
    public static function resetAll(): void
    {
        self::$instances = [];
    }
}
