<?php

namespace Metapraxis\WPFileSystem\Contracts;

/**
 * Defines the contract for all filesystem management operations.
 *
 * This interface groups methods responsible for managing file and directory
 * properties, such as permissions, ownership, and unique naming. It also
 * includes methods for server-level interactions like cache invalidation.
 *
 * @package Metapraxis\WPFileSystem\Contracts
 */
interface FSBaseManager extends FileSystem
{
    /**
     * Gets a filename that is sanitized and unique for the given directory.
     *
     * @param string $dir Directory.
     * @param string $filename File name.
     * @param callable|null $unique_filename_callback Callback. Default null.
     *
     * @see wp_unique_filename
     * @fires wpfs_before_ensure_unique_filename_action
     * @fires wpfs_after_ensure_unique_filename_action
     * @fires wpfs_ensure_unique_filename_filter
     * @return string New filename, if given wasn't unique.
     */
    public function ensureUniqueFilename(
        string $dir,
        string $filename,
        callable $unique_filename_callback = null
    ): string;

    /**
     * Changes the file's group.
     *
     * @param string $file Path to the file.
     * @param string|int $group The group name or ID.
     * @param bool $recursive Optional. Whether to change the group recursively.
     *
     * @see WP_Filesystem_Base::chgrp()
     * @fires wpfs_before_set_group_action
     * @fires wpfs_after_set_group_action
     * @return bool True on success, false on failure.
     */
    public function setGroup(string $file, $group, bool $recursive = false): bool;

    /**
     * Changes filesystem permissions.
     *
     * @param string $file Path to the file.
     * @param int|false $mode Optional. The file permissions in octal format.
     * @param bool $recursive Optional. Whether to change permissions recursively.
     *
     * @see WP_Filesystem_Base::chmod()
     * @fires wpfs_before_set_permissions_action
     * @fires wpfs_after_set_permissions_action
     * @return bool True on success, false on failure.
     */
    public function setPermissions(string $file, $mode = false, bool $recursive = false): bool;

    /**
     * Changes the owner of a file or directory.
     *
     * @param string $file Path to the file or directory.
     * @param string|int $owner The username or ID.
     * @param bool $recursive Optional. Whether to change the owner recursively.
     *
     * @see WP_Filesystem_Base::chown()
     * @fires wpfs_before_set_owner_action
     * @fires wpfs_after_set_owner_action
     * @return bool True on success, false on failure.
     */
    public function setOwner(string $file, $owner, bool $recursive = false): bool;

    /**
     * Changes the current directory.
     *
     * @param string $path The new current directory.
     *
     * @see WP_Filesystem_Base::chdir()
     * @fires wpfs_before_set_current_directory_action
     * @fires wpfs_after_set_current_directory_action
     * @return bool True on success, false on failure.
     */
    public function setCurrentDirectory(string $path): bool;

    /**
     * Invalidates a file's cache in OPcache.
     *
     * @param string $filepath Path to the file.
     * @param bool $force Force invalidation.
     *
     * @see wp_opcache_invalidate
     * @fires wpfs_before_invalidate_op_cache_action
     * @fires wpfs_after_invalidate_op_cache_action
     * @return bool Success of the operation.
     */
    public function invalidateOpCache(string $filepath, bool $force = false): bool;

    /**
     * Invalidates a directory's cache in OPcache.
     *
     * @param string $dir Path to the directory.
     *
     * @see wp_opcache_invalidate_directory
     * @fires wpfs_before_invalidate_directory_op_cache_action
     * @fires wpfs_after_invalidate_directory_op_cache_action
     * @return void
     */
    public function invalidateDirectoryOpCache(string $dir);
}
