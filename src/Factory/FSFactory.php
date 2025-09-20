<?php

namespace Metapraxis\WPFileSystem\Factory;

use Metapraxis\WPFileSystem\Adapters\FSAdvanced;
use Metapraxis\WPFileSystem\Adapters\FSBaseAction;
use Metapraxis\WPFileSystem\Adapters\FSBaseAuditor;
use Metapraxis\WPFileSystem\Adapters\FSBaseManager;
use Metapraxis\WPFileSystem\Adapters\FSBaseReader;
use Metapraxis\WPFileSystem\Decorator\FSDecorator;
use Metapraxis\WPFileSystem\Exceptions\FSInstanceTypeException;
use Metapraxis\WPFileSystem\Provider\FSGuardiansProvider;
use Metapraxis\WPFileSystem\Provider\FSHooksProvider;
use Metapraxis\WPFileSystem\Traits\NonInstantiable;
use Metapraxis\WPFileSystem\Contracts\FileSystem;
use Metapraxis\WPFileSystem\Contracts\FSBaseReader as BaseReader;
use Metapraxis\WPFileSystem\Contracts\FSBaseAction as BaseAction;
use Metapraxis\WPFileSystem\Contracts\FSBaseAuditor as BaseAuditor;
use Metapraxis\WPFileSystem\Contracts\FSBaseManager as BaseManager;
use Metapraxis\WPFileSystem\Contracts\FSAdvanced as Advanced;

/**
 * A factory for creating and managing filesystem service instances.
 *
 * This class implements the Singleton pattern to ensure that for each service
 * (Reader, Action, etc.), only one instance exists per request. This prevents
 * object duplication and ensures a predictable state.
 *
 * @package Metapraxis\WPFileSystem\Factory
 */
final class FSFactory
{
    use NonInstantiable;

    /**
     * Storage for already created service instances (Singleton implementation).
     *
     * @var array<string, FileSystem>
     */
    private static array $instances = [];

    /**
     * Universal method to create (or retrieve an existing) service instance.
     *
     * This is the main factory method that contains all the logic.
     * It is "lazy," meaning it creates an object only upon the first request.
     *
     * @template T of FileSystem
     * @param class-string<T> $className The class name of the service to create (e.g., FSBaseReader::class).
     * @return T Returns the singleton instance of the requested service.
     * @throws FSInstanceTypeException If an unknown or unsupported service type is requested.
     *
     * @example
     * // Get a reader instance
     * $reader = FSFactory::create(FSBaseReader::class);
     * $content = $reader->getContents("/path/to/file.txt");
     */
    public static function create(string $className): FileSystem
    {
        $guarded = FSGuardiansProvider::getStatus();
        $hookable = FSHooksProvider::getStatus();
        $cached = $guarded . $hookable . $className;

        if (isset(self::$instances[$cached])) {
            /** @var T */
            return self::$instances[$cached];
        }

        switch ($className) {
            case FSBaseAction::class:
            case FSBaseManager::class:
            case FSBaseAuditor::class:
            case FSAdvanced::class:
            case FSBaseReader::class:
                $instance = FSDecorator::create($className, $guarded, $hookable);
                break;
            default:
                throw new FSInstanceTypeException("The requested instance type $className is not supported.");
        }

        self::$instances[$cached] = $instance;

        return $instance;
    }

    /**
     * Gets the service for reading data from the filesystem.
     * A convenient shortcut for `create(FSBaseReader::class)`.
     *
     * @return BaseReader The instance of the service for read operations.
     * @throws FSInstanceTypeException In case of an internal error (unlikely).
     *
     * @example
     * $reader = FSFactory::getReader();
     * if ($reader->exists("/path/to/my-file.txt")) {
     *     $content = $reader->getContents("/path/to/my-file.txt");
     * }
     */
    public static function getReader(): FileSystem
    {
        return self::create(FSBaseReader::class);
    }

    /**
     * Gets the service for performing active file operations (copying, deleting, etc.).
     * A convenient shortcut for `create(FSBaseAction::class)`.
     *
     * @return BaseAction The instance of the service for active file operations.
     * @throws FSInstanceTypeException In case of an internal error (unlikely).
     *
     * @example
     * $action = FSFactory::getAction();
     * $action->copy("/path/to/source.log", "/path/to/destination.log");
     */
    public static function getAction(): FileSystem
    {
        return self::create(FSBaseAction::class);
    }

    /**
     * Gets the service for managing files and directories (permissions, names, cache).
     * A convenient shortcut for `create(FSBaseManager::class)`.
     *
     * @return BaseManager The instance of the service for filesystem management.
     * @throws FSInstanceTypeException In case of an internal error (unlikely).
     *
     * @example
     * $manager = FSFactory::getManager();
     * $safeName = $manager->sanitizeFilename("My File Name (2025)!.zip"); // "My-File-Name-2025.zip"
     * $manager->setPermissions("/path/to/important-file.php", 0644);
     */
    public static function getManager(): FileSystem
    {
        return self::create(FSBaseManager::class);
    }

    /**
     * Gets the service for checking and validating the state of the filesystem.
     * A convenient shortcut for `create(FSBaseAuditor::class)`.
     *
     * @return BaseAuditor The instance of the service for audit operations.
     * @throws FSInstanceTypeException In case of an internal error (unlikely).
     *
     * @example
     * $auditor = FSFactory::getAuditor();
     * if ($auditor->isDirectory("/path/to/uploads") && $auditor->isWritable("/path/to/uploads")) {
     *     // The directory exists and is writable.
     * }
     */
    public static function getAuditor(): FileSystem
    {
        return self::create(FSBaseAuditor::class);
    }

    /**
     * Gets the service for advanced, high-level filesystem operations.
     * A convenient shortcut for `create(FSAdvanced::class)`.
     *
     * @return Advanced The instance of the service for advanced operations.
     * @throws FSInstanceTypeException In case of an internal error (unlikely).
     *
     * @example $advanced = FSFactory::getAdvanced(); $advanced->atomicWrite('file.txt', 'content');
     */
    public static function getAdvanced(): FileSystem
    {
        return self::create(FSAdvanced::class);
    }
}
