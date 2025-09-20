<?php

namespace Metapraxis\WPFileSystem\Traits;

use Metapraxis\WPFileSystem\Exceptions\FSException;

trait NonInstantiable
{
    /**
     * The constructor is made private to prevent instantiation via `new`.
     */
    private function __construct()
    {
    }

    /**
     * Prevents cloning of the object.
     *
     * An attempt to clone will result in an exception, ensuring that
     * multiple instances cannot be created.
     *
     * @throws FSException As the operation is always forbidden.
     */
    public function __clone()
    {
        throw new FSException('Cloning the FSFactory is not allowed.');
    }

    /**
     * Prevents unserialization of the object.
     *
     * This prevents the creation of a new instance via `unserialize()`,
     * preserving the integrity of the Singleton pattern.
     *
     * @throws FSException As the operation is always forbidden.
     * @phpstan-ignore-next-line
     */
    public function __wakeup()
    {
        throw new FSException('Unserializing the FSFactory is not allowed.');
    }
}
