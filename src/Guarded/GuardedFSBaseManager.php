<?php

namespace Metapraxis\WPFileSystem\Guarded;

use Metapraxis\WPFileSystem\Assertion\Assertion;
use Metapraxis\WPFileSystem\Contracts\FSBaseManager;
use Metapraxis\WPFileSystem\Exceptions\FSException;
use Metapraxis\WPFileSystem\Exceptions\FSPathNotFoundException;
use Metapraxis\WPFileSystem\Exceptions\FSPermissionException;

/**
 * @extends GuardedFileSystem<FSBaseManager>
 */
class GuardedFSBaseManager extends GuardedFileSystem implements FSBaseManager
{
    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function ensureUniqueFilename(
        string $dir,
        string $filename,
        callable $unique_filename_callback = null
    ): string {
        return Assertion::validateResourceOperation(
            $this->fs->ensureUniqueFilename($dir, $filename, $unique_filename_callback),
            $filename
        );
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function setOwner(string $file, $owner, bool $recursive = false): bool
    {
        return Assertion::validateResourceOperation($this->fs->setOwner($file, $owner, $recursive), $file);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function setGroup(string $file, $group, bool $recursive = false): bool
    {
        return Assertion::validateResourceOperation($this->fs->setGroup($file, $group, $recursive), $file);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function setPermissions(string $file, $mode = false, bool $recursive = false): bool
    {
        return Assertion::validateResourceOperation($this->fs->setPermissions($file, $mode, $recursive), $file);
    }

    /**
     * @throws FSPermissionException
     * @throws FSPathNotFoundException
     * @throws FSException
     */
    public function setCurrentDirectory(string $path): bool
    {
        return Assertion::validateResourceOperation($this->fs->setCurrentDirectory($path), $path);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function invalidateOpCache(string $filepath, bool $force = false): bool
    {
        return Assertion::validateResourceOperation($this->fs->invalidateOpCache($filepath, $force), $filepath);
    }

    /**
     * @throws FSPathNotFoundException
     */
    public function invalidateDirectoryOpCache(string $dir)
    {
        Assertion::ensureResourceExists($dir);

        $this->fs->invalidateDirectoryOpCache($dir);
    }
}
