<?php

namespace Metapraxis\WPFileSystem\Adapters;

use Metapraxis\WPFileSystem\Contracts\FSBaseManager as BaseManager;

/**
 * @see tests/Unit/FSBaseManagerTest.php
 */
class FSBaseManager extends FileSystem implements BaseManager
{
    public function ensureUniqueFilename(
        string $dir,
        string $filename,
        callable $unique_filename_callback = null
    ): string {
        return wp_unique_filename($dir, $filename, $unique_filename_callback);
    }

    public function setOwner(string $file, $owner, bool $recursive = false): bool
    {
        return $this->wp_filesystem->chown($file, $owner, $recursive);
    }

    public function setGroup(string $file, $group, bool $recursive = false): bool
    {
        return $this->wp_filesystem->chgrp($file, $group, $recursive);
    }

    public function setPermissions(string $file, $mode = false, bool $recursive = false): bool
    {
        return $this->wp_filesystem->chmod($file, $mode, $recursive);
    }

    public function setCurrentDirectory(string $path): bool
    {
        return $this->wp_filesystem->chdir($path);
    }

    public function invalidateOpCache(string $filepath, bool $force = false): bool
    {
        return wp_opcache_invalidate($filepath, $force);
    }

    public function invalidateDirectoryOpCache(string $dir)
    {
        wp_opcache_invalidate_directory($dir);
    }
}
