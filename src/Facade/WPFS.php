<?php

namespace Metapraxis\WPFileSystem\Facade;

use BadMethodCallException;
use DOMDocument;
use Metapraxis\WPFileSystem\Exceptions\FSInstanceTypeException;
use Metapraxis\WPFileSystem\Factory\FSFactory;
use Metapraxis\WPFileSystem\Provider\FSStorageProvider;
use SimpleXMLElement;

/**
 * Enhanced WordPress Filesystem Facade
 *
 * Extended adapter for WordPress Filesystem API
 * Includes all base WP_Filesystem methods + additional methods + WordPress utilities
 *
 * --- FSReader: Methods for reading files, directories, and properties. ---
 * @method static string getHomePath()
 * @method static string getInstallationPath()
 * @method static string getContentPath()
 * @method static string getPluginsPath()
 * @method static string getThemesPath(string|false $theme = false)
 * @method static string getLangPath()
 * @method static string getHumanReadablePermissions(string $file)
 * @method static string getPermissions(string $file)
 * @method static string|false getContents(string $file)
 * @method static array|false getContentsAsArray(string $file)
 * @method static string|false getCurrentPath()
 * @method static string|false getOwner(string $file)
 * @method static string|false getGroup(string $file)
 * @method static int|false getLastAccessedTime(string $file)
 * @method static int|false getLastModifiedTime(string $file)
 * @method static int|false getFileSize(string $file)
 * @method static string getPermissionsAsOctal(string $mode)
 * @method static array|false getDirectoryList(string $path, bool $includeHidden = true, bool $recursive = false)
 * // phpcs:ignore Generic.Files.LineLength.TooLong
 * @method static array getFiles(string $folder = '', int $levels = 100, array $exclusions = [], bool $includeHidden = false)
 * @method static array getUploadsDirInfo(?string $time = null, bool $create_dir = true, bool $refresh_cache = false)
 * @method static string getTempDir()
 * @method static string getNormalizePath(string $path)
 * @method static string getSanitizeFilename(string $filename)
 * @method static string|false findFolder(string $folder)
 * @method static string|false searchForFolder(string $folder, string $base = '.', bool $loop = false)
 *
 * --- FSAction: Methods for creating, modifying, and deleting files and directories. ---
 * @method static bool putContents(string $file, string $contents, ?int $mode = null)
 * @method static bool copyFile(string $source, string $destination, bool $overwrite = false, ?int $mode = null)
 * @method static bool copyDirectory(string $from, string $to, array $skipList = [])
 * @method static bool moveFile(string $source, string $destination, bool $overwrite = false)
 * @method static bool moveDirectory(string $from, string $to, bool $overwrite = false)
 * @method static bool delete(string $path, bool $recursive = false, ?string $type = null)
 * @method static bool touch(string $file, int $mtime = 0, int $atime = 0)
 * @method static bool createDirectory(string $path, ?int $chmod = null, mixed $chown = null, mixed $chgrp = null)
 * @method static string|false createTempFile(string $filename = '', string $dir = '')
 * @method static bool deleteDirectory(string $path, bool $recursive = false)
 * @method static array handleUpload(array $file, array|false $overrides = false, ?string $time = null)
 * @method static array handleSideload(array $file, array|false $overrides = false, ?string $time = null)
 * @method static string|false downloadFromUrl(string $url, int $timeout = 300, bool $signatureVerification = false)
 * @method static bool unzip(string $file, string $to)
 *
 * --- FSAuditor: Methods for checking file and directory states and properties. ---
 * @method static bool isBinary(string $text)
 * @method static bool isFile(string $path)
 * @method static bool isDirectory(string $path)
 * @method static bool isReadable(string $path)
 * @method static bool isWritable(string $path)
 * @method static bool exists(string $path)
 * @method static bool connect()
 * @method static bool|int verifyMd5(string $filename, string $expectedMd5)
 * // phpcs:ignore Generic.Files.LineLength.TooLong
 * @method static bool verifySignature(string $filename, string|array $signatures, string|false $filenameForErrors = false)
 * @method static bool isZipFile(string $file)
 *
 * --- FSManager: Methods for managing file permissions, ownership, and server state. ---
 * @method static string ensureUniqueFilename(string $dir, string $filename, ?callable $unique_filename_callback = null)
 * @method static bool setOwner(string $file, string|int $owner, bool $recursive = false)
 * @method static bool setGroup(string $file, string|int $group, bool $recursive = false)
 * @method static bool setPermissions(string $file, ?int $mode = null, bool $recursive = false)
 * @method static bool setCurrentDirectory(string $path)
 * @method static bool invalidateOpCache(string $filepath, bool $force = false)
 * @method static void invalidateDirectoryOpCache(string $dir)
 *
 * --- FSAdvanced: Additional high-level and utility methods. ---
 * @method static bool atomicWrite(string $path, string $content, ?int $mode = null)
 * @method static bool append(string $path, string $content)
 * @method static bool prepend(string $path, string $content)
 * @method static bool replace(string $path, string|array $search, string|array $replace)
 * @method static string extension(string $path)
 * @method static string filename(string $path)
 * @method static string dirname(string $path)
 * @method static bool cleanDirectory(string $directory)
 * @method static bool isDirectoryEmpty(string $directory)
 * @method static string|false getMimeType(string $path)
 * @method static string|false hash(string $path, string $algorithm = 'md5')
 * @method static bool filesEqual(string $path1, string $path2)
 * @method static mixed readJson(string $path, bool $assoc = true)
 * @method static bool writeJson(string $path, mixed $data, int $options = 128)
 * @method static array glob(string $pattern, int $flags = 0)
 * @method static SimpleXMLElement|false readXml(string $path)
 * @method static bool writeXml(string $path, DOMDocument|SimpleXMLElement|string $xml)
 * @method static DOMDocument|false readDom(string $path)
 * @method static bool writeDom(string $path, DOMDocument|SimpleXMLElement|string $dom)
 */
final class WPFS
{
    /**
     * Magic method to handle dynamic method calls.
     * Delegates calls to appropriate filesystem providers.
     *
     * @param string $name Method name being called
     * @param array $arguments Arguments passed to the method
     *
     * @return mixed Result from the delegated method call
     * @throws BadMethodCallException When a method is not found in any provider
     * @throws FSInstanceTypeException
     */
    public static function __callStatic(string $name, array $arguments)
    {
        foreach (FSStorageProvider::getProviders() as $provider) {
            $provider = FSFactory::create($provider);

            if (method_exists($provider, $name)) {
                return $provider->$name(...$arguments);
            }
        }

        throw new BadMethodCallException(sprintf('Method "%s" does not exist.', $name));
    }
}
