<?php

namespace Metapraxis\WPFileSystem\Contracts;

use WP_Error;

/**
 * Defines the contract for all filesystem action operations.
 *
 * This interface groups methods responsible for modifying the filesystem,
 * such as writing, copying, moving, and deleting files and directories.
 *
 * @package Metapraxis\WPFileSystem\Contracts
 */
interface FSBaseAction extends FileSystem
{
    /**
     * Writes a string to a file.
     *
     * @param string $file Path to the file to write to.
     * @param string $contents The data to write.
     * @param int|false $mode Optional. The file permissions in octal format (e.g., 0644).
     *
     * @see WP_Filesystem_Base::put_contents()
     * @fires wpfs_before_put_contents_action
     * @fires wpfs_after_put_contents_action
     * @return bool True on success, false on failure.
     */
    public function putContents(string $file, string $contents, $mode = false): bool;

    /**
     * Copies a file.
     *
     * @param string $source Path to the source file.
     * @param string $destination Path to the destination file.
     * @param bool $overwrite Optional. Whether to overwrite the destination file.
     * @param int|false $mode Optional. The file permissions in octal format.
     *
     * @see WP_Filesystem_Base::copy()
     * @fires wpfs_before_copy_file_action
     * @fires wpfs_after_copy_file_action
     * @return bool True on success, false on failure.
     */
    public function copyFile(string $source, string $destination, bool $overwrite = false, $mode = false): bool;

    /**
     * Copies a directory from one location to another.
     *
     * @param string $from Source directory.
     * @param string $to Destination directory.
     * @param string[] $skipList List of files/folders to exclude.
     *
     * @see copy_dir
     * @fires wpfs_before_copy_directory_action
     * @fires wpfs_after_copy_directory_action
     * @return true|WP_Error True on success, otherwise WP_Error.
     */
    public function copyDirectory(string $from, string $to, array $skipList = []);

    /**
     * Moves a file.
     *
     * @param string $source Path to the source file.
     * @param string $destination Path to the destination file.
     * @param bool $overwrite Optional. Whether to overwrite the destination file.
     *
     * @see WP_Filesystem_Base::move()
     * @fires wpfs_before_move_file_action
     * @fires wpfs_after_move_file_action
     * @return bool True on success, false on failure.
     */
    public function moveFile(string $source, string $destination, bool $overwrite = false): bool;

    /**
     * Moves a directory from one location to another.
     *
     * @param string $from Source directory.
     * @param string $to Destination directory.
     * @param bool $overwrite Overwrite if the destination exists.
     *
     * @see move_dir
     * @fires wpfs_before_move_directory_action
     * @fires wpfs_after_move_directory_action
     * @return true|WP_Error True on success, otherwise WP_Error.
     */
    public function moveDirectory(string $from, string $to, bool $overwrite = false);

    /**
     * Deletes a file or directory.
     *
     * @param string $path Path to the file or directory.
     * @param bool $recursive Optional. Whether to delete it recursively.
     * @param string|false $type Optional. Resource type: 'f' for a file, 'd' for a directory.
     *
     * @see WP_Filesystem_Base::delete()
     * @fires wpfs_before_delete_action
     * @fires wpfs_after_delete_action
     * @return bool True on success, false on failure.
     */
    public function delete(string $path, bool $recursive = false, $type = false): bool;

    /**
     * Sets the access and modification times of a file.
     *
     * @param string $file Path to the file.
     * @param int $mtime The modification time.
     * @param int $atime The access time.
     *
     * @see WP_Filesystem_Base::touch()
     * @fires wpfs_before_touch_action
     * @fires wpfs_after_touch_action
     * @return bool True on success, false on failure.
     */
    public function touch(string $file, int $mtime = 0, int $atime = 0): bool;

    /**
     * Creates a directory.
     *
     * @param string $path The path to create.
     * @param int|false $chmod Optional. Permissions in octal format.
     * @param string|int|false $chown Optional. Owner name or ID.
     * @param string|int|false $chgrp Optional. Group name or ID.
     *
     * @see WP_Filesystem_Base::mkdir()
     * @fires wpfs_before_create_directory_action
     * @fires wpfs_after_create_directory_action
     * @return bool True on success, false on failure.
     */
    public function createDirectory(string $path, $chmod = false, $chown = false, $chgrp = false): bool;

    /**
     * Creates a unique temporary filename.
     *
     * @param string $filename Optional. The filename to base the unique name on.
     * @param string $dir Optional. The directory for storing the file.
     *
     * @see wp_tempnam
     * @fires wpfs_before_create_temp_file_action
     * @fires wpfs_after_create_temp_file_action
     * @fires wpfs_create_temp_file_filter
     * @return string Path to the temporary file.
     */
    public function createTempFile(string $filename = '', string $dir = ''): string;

    /**
     * Removes a directory.
     *
     * @param string $path Path to the directory.
     * @param bool $recursive Optional. Whether to remove recursively.
     *
     * @see WP_Filesystem_Base::rmdir()
     * @fires wpfs_before_delete_directory_action
     * @fires wpfs_after_delete_directory_action
     * @return bool True on success, false on failure.
     */
    public function deleteDirectory(string $path, bool $recursive = false): bool;

    /**
     * Handles a file upload, moving it to the WordPress uploads directory.
     *
     * @param array $file Reference to an element from `$_FILES`.
     * @param array|false $overrides Optional. An array to override variables.
     * @param string|null $time Optional. Time in 'yyyy/mm' format.
     *
     * @see wp_handle_upload
     * @fires wpfs_before_handle_upload_action
     * @fires wpfs_after_handle_upload_action
     * @fires wpfs_handle_upload_filter
     * @return array See `wp_handle_upload()`
     */
    public function handleUpload(array &$file, $overrides = false, string $time = null): array;

    /**
     * Handles a sideloaded file, moving it to the WordPress uploads directory.
     *
     * @param array $file Reference to an element from `$_FILES`.
     * @param array|false $overrides Optional. An array to override variables.
     * @param string|null $time Optional. Time in 'yyyy/mm' format.
     *
     * @see wp_handle_sideload
     * @fires wpfs_before_handle_sideload_action
     * @fires wpfs_after_handle_sideload_action
     * @fires wpfs_handle_sideload_filter
     * @return array See `wp_handle_sideload()`
     */
    public function handleSideload(array &$file, $overrides = false, string $time = null): array;

    /**
     * Downloads a file from a URL to a temporary local file.
     *
     * @param string $url URL of the file to download.
     * @param int $timeout Timeout for the request.
     * @param bool $signatureVerification Whether to perform signature verification.
     *
     * @see download_url
     * @fires wpfs_before_download_from_url_action
     * @fires wpfs_after_download_from_url_action
     * @fires wpfs_download_from_url_filter
     * @return string|WP_Error Filename on success, otherwise WP_Error.
     */
    public function downloadFromUrl(string $url, int $timeout = 300, bool $signatureVerification = false);

    /**
     * Unzips a ZIP archive.
     *
     * @param string $file Full path to the ZIP file.
     * @param string $to Full path to the destination directory.
     *
     * @see unzip_file
     * @fires wpfs_before_unzip_action
     * @fires wpfs_after_unzip_action
     * @return true|WP_Error True on success, otherwise WP_Error.
     */
    public function unzip(string $file, string $to);
}
