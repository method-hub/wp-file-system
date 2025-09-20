<?php

namespace Metapraxis\WPFileSystem\Provider;

use Metapraxis\WPFileSystem\Adapters\FSAdvanced;
use Metapraxis\WPFileSystem\Adapters\FSBaseAction;
use Metapraxis\WPFileSystem\Adapters\FSBaseAuditor;
use Metapraxis\WPFileSystem\Adapters\FSBaseManager;
use Metapraxis\WPFileSystem\Adapters\FSBaseReader;
use Metapraxis\WPFileSystem\Contracts\FileSystem;
use Metapraxis\WPFileSystem\Traits\NonInstantiable;

/**
 * Service Provider for the filesystem.
 *
 * This class acts as a registry, providing a single list of all
 * available service classes (adapters). The WPFS facade uses this list
 * to determine which service to search for the called method.
 *
 * @final
 */
final class FSStorageProvider
{
    use NonInstantiable;

    /**
     * An ordered list of all default provider classes.
     *
     * The facade will iterate through this array in the specified order when assertions are disabled.
     * The more frequently a class's methods are used, the higher it should be in the list
     * for a minor performance optimization. These providers mimic the native WP_Filesystem
     * behavior, typically returning false on failure.
     *
     * @var array<int, class-string<FileSystem>>
     */
    private static array $providers = [
        FSBaseReader::class,
        FSBaseAuditor::class,
        FSBaseAction::class,
        FSBaseManager::class,
        FSAdvanced::class
    ];

    /**
     * Gets the currently active list of provider classes.
     *
     * The method determines which set of providers to return based on a priority system:
     * 1. If custom providers have been set, they are returned.
     * 2. If assertions are enabled, the throwable providers are returned.
     * 3. Otherwise, the default providers are returned.
     *
     * @return array<int, class-string<FileSystem>> The active list of provider class names.
     */
    public static function getProviders(): array
    {
        return self::$providers;
    }
}
