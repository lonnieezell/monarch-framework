<?php

declare(strict_types=1);

namespace Monarch\Concerns;

use Monarch\Factory;

trait IsSingleton
{
    /**
     * Generates a new instance of the class if one does not exist.
     */
    public static function instance()
    {
        return Factory::get(static::class);
    }

    /**
     * Sets the instance of the class that should be
     * returned by the instance() method.
     */
    public static function setInstance($instance): void
    {
        Factory::set(static::class, $instance);
    }

    /**
     * Resets the instance of the class.
     */
    public static function reset(): void
    {
        Factory::resetClass(static::class);
    }
}
