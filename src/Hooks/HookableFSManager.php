<?php

namespace Metapraxis\WPFileSystem\Hooks;

use Metapraxis\WPFileSystem\Contracts\FSBaseManager;
use Metapraxis\WPFileSystem\Hooks\Collection\ManagerHooks;

/**
 * @extends HookableFileSystem<FSBaseManager>
 */
class HookableFSManager extends HookableFileSystem implements FSBaseManager
{
    use ManagerHooks;

    public function ensureUniqueFilename(
        string $dir,
        string $filename,
        callable $unique_filename_callback = null
    ): string {
        do_action(self::$BEFORE_ENSURE_UNIQUE_FILENAME_ACTION, $dir, $filename, $unique_filename_callback);
        $result = $this->fs->ensureUniqueFilename($dir, $filename, $unique_filename_callback);
        $result = apply_filters(
            self::$ENSURE_UNIQUE_FILENAME_FILTER,
            $result,
            $dir,
            $filename,
            $unique_filename_callback
        );
        do_action(self::$AFTER_ENSURE_UNIQUE_FILENAME_ACTION, $result, $dir, $filename, $unique_filename_callback);

        return $result;
    }

    public function setGroup(string $file, $group, bool $recursive = false): bool
    {
        do_action(self::$BEFORE_SET_GROUP_ACTION, $file, $group, $recursive);
        $result = $this->fs->setGroup($file, $group, $recursive);
        do_action(self::$AFTER_SET_GROUP_ACTION, $result, $file, $group, $recursive);

        return $result;
    }

    public function setPermissions(string $file, $mode = false, bool $recursive = false): bool
    {
        do_action(self::$BEFORE_SET_PERMISSIONS_ACTION, $file, $mode, $recursive);
        $result = $this->fs->setPermissions($file, $mode, $recursive);
        do_action(self::$AFTER_SET_PERMISSIONS_ACTION, $result, $file, $mode, $recursive);

        return $result;
    }

    public function setOwner(string $file, $owner, bool $recursive = false): bool
    {
        do_action(self::$BEFORE_SET_OWNER_ACTION, $file, $owner, $recursive);
        $result = $this->fs->setOwner($file, $owner, $recursive);
        do_action(self::$AFTER_SET_OWNER_ACTION, $result, $file, $owner, $recursive);

        return $result;
    }

    public function setCurrentDirectory(string $path): bool
    {
        do_action(self::$BEFORE_SET_CURRENT_DIRECTORY_ACTION, $path);
        $result = $this->fs->setCurrentDirectory($path);
        do_action(self::$AFTER_SET_CURRENT_DIRECTORY_ACTION, $result, $path);

        return $result;
    }

    public function invalidateOpCache(string $filepath, bool $force = false): bool
    {
        do_action(self::$BEFORE_INVALIDATE_OP_CACHE_ACTION, $filepath, $force);
        $result = $this->fs->invalidateOpCache($filepath, $force);
        do_action(self::$AFTER_INVALIDATE_OP_CACHE_ACTION, $result, $filepath, $force);

        return $result;
    }

    public function invalidateDirectoryOpCache(string $dir)
    {
        do_action(self::$BEFORE_INVALIDATE_DIRECTORY_OP_CACHE_ACTION, $dir);
        $this->fs->invalidateDirectoryOpCache($dir);
        do_action(self::$AFTER_INVALIDATE_DIRECTORY_OP_CACHE_ACTION, $dir);
    }
}
