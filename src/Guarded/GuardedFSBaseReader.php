<?php

namespace Metapraxis\WPFileSystem\Guarded;

use Metapraxis\WPFileSystem\Assertion\Assertion;
use Metapraxis\WPFileSystem\Contracts\FSBaseReader;
use Metapraxis\WPFileSystem\Exceptions\FSException;
use Metapraxis\WPFileSystem\Exceptions\FSPathNotFoundException;
use Metapraxis\WPFileSystem\Exceptions\FSPermissionException;

/**
 * @extends GuardedFileSystem<FSBaseReader>
 */
final class GuardedFSBaseReader extends GuardedFileSystem implements FSBaseReader
{
    /**
     * @throws FSException
     */
    public function getHomePath(): string
    {
        return Assertion::ensureSuccessful(get_home_path());
    }

    /**
     * @throws FSException
     */
    public function getInstallationPath(): string
    {
        return Assertion::ensureSuccessful($this->fs->getInstallationPath());
    }

    /**
     * @throws FSException
     */
    public function getContentPath(): string
    {
        return Assertion::ensureSuccessful($this->fs->getContentPath());
    }

    /**
     * @throws FSException
     */
    public function getPluginsPath(): string
    {
        return Assertion::ensureSuccessful($this->fs->getPluginsPath());
    }

    /**
     * @throws FSException
     */
    public function getThemesPath($theme = false): string
    {
        return Assertion::ensureSuccessful($this->fs->getThemesPath($theme));
    }

    /**
     * @throws FSException
     */
    public function getLangPath(): string
    {
        return Assertion::ensureSuccessful($this->fs->getLangPath());
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function getHumanReadablePermissions(string $file): string
    {
        return Assertion::validateResourceOperation($this->fs->getHumanReadablePermissions($file), $file);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function getPermissions(string $file): string
    {
        return Assertion::validateResourceOperation($this->fs->getPermissions($file), $file);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function getContents(string $file)
    {
        return Assertion::validateResourceOperation($this->fs->getContents($file), $file);
    }

    /**
     * @throws FSPermissionException
     * @throws FSPathNotFoundException
     * @throws FSException
     */
    public function getContentsAsArray(string $file)
    {
        return Assertion::validateResourceOperation($this->fs->getContentsAsArray($file), $file);
    }

    /**
     * @throws FSException
     */
    public function getCurrentPath()
    {
        return Assertion::ensureSuccessful($this->fs->getCurrentPath());
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function getOwner(string $file)
    {
        return Assertion::validateResourceOperation($this->fs->getOwner($file), $file);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function getGroup(string $file)
    {
        return Assertion::validateResourceOperation($this->fs->getGroup($file), $file);
    }

    /**
     * @throws FSPermissionException
     * @throws FSPathNotFoundException
     * @throws FSException
     */
    public function getLastAccessedTime(string $file)
    {
        return Assertion::validateResourceOperation($this->fs->getLastAccessedTime($file), $file);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function getLastModifiedTime(string $file)
    {
        return Assertion::validateResourceOperation($this->fs->getLastModifiedTime($file), $file);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function getFileSize(string $file)
    {
        return Assertion::validateResourceOperation($this->fs->getFileSize($file), $file);
    }

    /**
     * @throws FSException
     */
    public function getPermissionsAsOctal(string $mode): string
    {
        return Assertion::ensureSuccessful($this->fs->getPermissionsAsOctal($mode));
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function getDirectoryList(string $path, bool $includeHidden = true, bool $recursive = false)
    {
        return Assertion::validateResourceOperation(
            $this->fs->getDirectoryList($path, $includeHidden, $recursive),
            $path
        );
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function getFiles(
        string $folder = '',
        int $levels = 100,
        array $exclusions = [],
        bool $includeHidden = false
    ) {
        return Assertion::validateResourceOperation(
            $this->fs->getFiles($folder, $levels, $exclusions, $includeHidden),
            $folder
        );
    }

    /**
     * @throws FSException
     */
    public function getUploadsDirInfo(?string $time = null, bool $create_dir = true, bool $refresh_cache = false): array
    {
        return Assertion::ensureSuccessful($this->fs->getUploadsDirInfo($time, $create_dir, $refresh_cache));
    }

    /**
     * @throws FSException
     */
    public function getTempDir(): string
    {
        return Assertion::ensureSuccessful($this->fs->getTempDir());
    }

    /**
     * @throws FSPermissionException
     * @throws FSPathNotFoundException
     * @throws FSException
     */
    public function getNormalizePath(string $path): string
    {
        return Assertion::validateResourceOperation($this->fs->getNormalizePath($path), $path);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function getSanitizeFilename(string $filename): string
    {
        return Assertion::validateResourceOperation($this->fs->getSanitizeFilename($filename), $filename);
    }

    /**
     * @throws FSPermissionException
     * @throws FSPathNotFoundException
     * @throws FSException
     */
    public function findFolder(string $folder)
    {
        return Assertion::validateResourceOperation($this->fs->findFolder($folder), $folder);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function searchForFolder(string $folder, string $base = '.', bool $loop = false)
    {
        return Assertion::validateResourceOperation($this->fs->searchForFolder($folder, $base, $loop), $folder);
    }
}
