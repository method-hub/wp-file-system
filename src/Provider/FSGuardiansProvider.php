<?php

namespace Metapraxis\WPFileSystem\Provider;

use Metapraxis\WPFileSystem\Traits\NonInstantiable;

final class FSGuardiansProvider
{
    use NonInstantiable;

    /**
     * @var bool A flag that enables or disables the assertion mechanism. Disabled by default.
     */
    private static bool $enabled = false;

    /**
     * Sets the global state of the assertion mechanism.
     *
     * @param bool $state True to enable, false to disable.
     */
    public static function setStatus(bool $state): void
    {
        self::$enabled = $state;
    }

    /**
     * Returns the current state of the assertion mechanism.
     *
     * @return bool True if assertions are enabled, otherwise false.
     */
    public static function getStatus(): bool
    {
        return self::$enabled;
    }
}
