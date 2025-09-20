<?php

namespace Metapraxis\WPFileSystem\Contracts;

/**
 * Defines the contract for all filesystem read operations.
 *
 * This interface gathers all methods responsible for retrieving information
 * from the filesystem, such as reading file contents, listing directories,
 * and checking file properties, without modifying any data.
 *
 * @package Metapraxis\WPFileSystem\Contracts
 */
interface FSBaseReader extends FileSystem
{
    /**
     * Retrieves the absolute path to the WordPress root directory.
     *
     * @see get_home_path
     * @fires wpfs_before_get_home_path_action
     * @fires wpfs_after_get_home_path_action
     * @fires wpfs_get_home_path_filter
     * @return string Full path to the root directory.
     */
    public function getHomePath(): string;

    /**
     * Returns the absolute path to the WordPress directory.
     *
     * @see WP_Filesystem_Base::abspath()
     * @fires wpfs_before_get_installation_path_action
     * @fires wpfs_after_get_installation_path_action
     * @fires wpfs_get_installation_path_filter
     * @return string The absolute path to the WordPress installation.
     */
    public function getInstallationPath(): string;

    /**
     * Returns the path to the wp-content directory.
     *
     * @see WP_Filesystem_Base::wp_content_dir()
     * @fires wpfs_before_get_content_path_action
     * @fires wpfs_after_get_content_path_action
     * @fires wpfs_get_content_path_filter
     * @return string The path to the wp-content directory.
     */
    public function getContentPath(): string;

    /**
     * Returns the path to the plugins' directory.
     *
     * @see WP_Filesystem_Base::wp_plugins_dir()
     * @fires wpfs_before_get_plugins_path_action
     * @fires wpfs_after_get_plugins_path_action
     * @fires wpfs_get_plugins_path_filter
     * @return string The path to the plugins' directory.
     */
    public function getPluginsPath(): string;

    /**
     * Returns the path to the themes' directory.
     *
     * @param string|false $theme Optional. The theme to get the directory for.
     *
     * @see WP_Filesystem_Base::wp_themes_dir()
     * @fires wpfs_before_get_themes_path_action
     * @fires wpfs_after_get_themes_path_action
     * @fires wpfs_get_themes_path_filter
     * @return string The path to the themes' directory.
     */
    public function getThemesPath($theme = false): string;

    /**
     * Returns the path to the language directory.
     *
     * @see WP_Filesystem_Base::wp_lang_dir()
     * @fires wpfs_before_get_lang_path_action
     * @fires wpfs_after_get_lang_path_action
     * @fires wpfs_get_lang_path_filter
     * @return string The path to the language directory.
     */
    public function getLangPath(): string;

    /**
     * Gets the file permissions in human-readable (*nix) format.
     *
     * @param string $file The file name.
     *
     * @see WP_Filesystem_Base::gethchmod()
     * @fires wpfs_before_get_human_readable_permissions_action
     * @fires wpfs_after_get_human_readable_permissions_action
     * @fires wpfs_get_human_readable_permissions_filter
     * @return string A *nix-style representation of the file permissions (e.g., 'drwxr-xr-x').
     */
    public function getHumanReadablePermissions(string $file): string;

    /**
     * Gets the file permissions in octal format.
     *
     * @param string $file Path to the file.
     *
     * @see WP_Filesystem_Base::getchmod()
     * @fires wpfs_before_get_permissions_action
     * @fires wpfs_after_get_permissions_action
     * @fires wpfs_get_permissions_filter
     * @return string The file mode in octal format (e.g., '0644').
     */
    public function getPermissions(string $file): string;

    /**
     * Reads the entire contents of a file into a string.
     *
     * @param string $file The name of the file to read.
     *
     * @see WP_Filesystem_Base::get_contents()
     * @fires wpfs_before_get_contents_action
     * @fires wpfs_after_get_contents_action
     * @fires wpfs_get_contents_filter
     * @return string|false The file data on success, false on failure.
     */
    public function getContents(string $file);

    /**
     * Reads the entire contents of a file into an array of lines.
     *
     * @param string $file Path to the file.
     *
     * @see WP_Filesystem_Base::get_contents_array()
     * @fires wpfs_before_get_contents_as_array_action
     * @fires wpfs_after_get_contents_as_array_action
     * @fires wpfs_get_contents_as_array_filter
     * @return array|false The file contents as an array on success, false on failure.
     */
    public function getContentsAsArray(string $file);

    /**
     * Gets the current working directory.
     *
     * @see WP_Filesystem_Base::cwd()
     * @fires wpfs_before_get_current_path_action
     * @fires wpfs_after_get_current_path_action
     * @fires wpfs_get_current_path_filter
     * @return string|false The current working directory on success, false on failure.
     */
    public function getCurrentPath();

    /**
     * Gets the owner of a file.
     *
     * @param string $file Path to the file.
     *
     * @see WP_Filesystem_Base::owner()
     * @fires wpfs_before_get_owner_action
     * @fires wpfs_after_get_owner_action
     * @fires wpfs_get_owner_filter
     * @return string|false The username of the owner on success, false on failure.
     */
    public function getOwner(string $file);

    /**
     * Gets the group of files.
     *
     * @param string $file Path to the file.
     *
     * @see WP_Filesystem_Base::group()
     * @fires wpfs_before_get_group_action
     * @fires wpfs_after_get_group_action
     * @fires wpfs_get_group_filter
     * @return string|false The group on success, false on failure.
     */
    public function getGroup(string $file);

    /**
     * Gets the last access time of a file.
     *
     * @param string $file Path to the file.
     *
     * @see WP_Filesystem_Base::atime()
     * @fires wpfs_before_get_last_accessed_time_action
     * @fires wpfs_after_get_last_accessed_time_action
     * @fires wpfs_get_last_accessed_time_filter
     * @return int|false The timestamp on success, false on failure.
     */
    public function getLastAccessedTime(string $file);

    /**
     * Gets the last modification time of a file.
     *
     * @param string $file Path to the file.
     *
     * @see WP_Filesystem_Base::mtime()
     * @fires wpfs_before_get_last_modified_time_action
     * @fires wpfs_after_get_last_modified_time_action
     * @fires wpfs_get_last_modified_time_filter
     * @return int|false The timestamp on success, false on failure.
     */
    public function getLastModifiedTime(string $file);

    /**
     * Gets the size of a file.
     *
     * @param string $file Path to the file.
     *
     * @see WP_Filesystem_Base::size()
     * @fires wpfs_before_get_file_size_action
     * @fires wpfs_after_get_file_size_action
     * @fires wpfs_get_file_size_filter
     * @return int|false The file size on success, false on failure.
     */
    public function getFileSize(string $file);

    /**
     * Converts permissions from human-readable (*nix) to an octal number.
     *
     * @param string $mode The *nix-style permissions.
     *
     * @see WP_Filesystem_Base::getnumchmodfromh()
     * @fires wpfs_before_get_permissions_as_octal_action
     * @fires wpfs_after_get_permissions_as_octal_action
     * @fires wpfs_get_permissions_as_octal_filter
     * @return string The octal representation of the permissions.
     */
    public function getPermissionsAsOctal(string $mode): string;

    /**
     * Gets a detailed list of files and directories.
     *
     * @param string $path Path to the directory.
     * @param bool $includeHidden Optional. Whether to include hidden files.
     * @param bool $recursive Optional. Whether to list recursively.
     *
     * @see WP_Filesystem_Base::dirlist()
     * @fires wpfs_before_get_directory_list_action
     * @fires wpfs_after_get_directory_list_action
     * @fires wpfs_get_directory_list_filter
     * @return array|false An array of file/directory information on success, false on failure.
     */
    public function getDirectoryList(string $path, bool $includeHidden = true, bool $recursive = false);

    /**
     * Returns a recursive list of all files in a directory.
     *
     * @param string $folder Optional. Full path to the folder.
     * @param int $levels Optional. The depth of subdirectories to scan.
     * @param string[] $exclusions Optional. List of files and folders to exclude.
     * @param bool $includeHidden Optional. Whether to include hidden files.
     *
     * @see list_files
     * @fires wpfs_before_get_files_action
     * @fires wpfs_after_get_files_action
     * @fires wpfs_get_files_filter
     * @return string[]|false An array of files on success, otherwise false.
     */
    public function getFiles(
        string $folder = '',
        int $levels = 100,
        array $exclusions = [],
        bool $includeHidden = false
    );

    /**
     * Returns an array containing the current upload directory's path and URL.
     *
     * @param string|null $time Optional. Time formatted in 'yyyy/mm'. Default null.
     * @param bool $create_dir Optional. Whether to check and create the uploads' directory.
     * @param bool $refresh_cache Optional. Whether to refresh the cache. Default false.
     *
     * @see wp_upload_dir
     * @fires wpfs_before_get_uploads_dir_info_action
     * @fires wpfs_after_get_uploads_dir_info_action
     * @fires wpfs_get_uploads_dir_info_filter
     * @return array Array of information about the upload directory.
     */
    public function getUploadsDirInfo(
        ?string $time = null,
        bool $create_dir = true,
        bool $refresh_cache = false
    ): array;

    /**
     * Determines a writable directory for temporary files.
     *
     * @see get_temp_dir
     * @fires wpfs_before_get_temp_dir_action
     * @fires wpfs_after_get_temp_dir_action
     * @fires wpfs_get_temp_dir_filter
     * @return string Writable temporary directory.
     */
    public function getTempDir(): string;

    /**
     * Normalizes a filesystem path.
     *
     * @param string $path Path to normalize.
     *
     * @see wp_normalize_path
     * @fires wpfs_before_get_normalize_path_action
     * @fires wpfs_after_get_normalize_path_action
     * @fires wpfs_get_normalize_path_filter
     * @return string Normalized path.
     */
    public function getNormalizePath(string $path): string;

    /**
     * Sanitizes a filename, replacing whitespace with dashes.
     *
     * @param string $filename The filename to be sanitized.
     *
     * @see sanitize_file_name
     * @fires wpfs_before_get_sanitize_filename_action
     * @fires wpfs_after_get_sanitize_filename_action
     * @fires wpfs_get_sanitize_filename_filter
     * @return string The sanitized filename.
     */
    public function getSanitizeFilename(string $filename): string;

    /**
     * Finds a folder on the remote filesystem.
     *
     * @param string $folder The folder to find.
     *
     * @see WP_Filesystem_Base::find_folder()
     * @fires wpfs_before_find_folder_action
     * @fires wpfs_after_find_folder_action
     * @fires wpfs_find_folder_filter
     * @return string|false The location of the remote path, or false on failure.
     */
    public function findFolder(string $folder);

    /**
     * Searches for a folder on the remote filesystem.
     *
     * @param string $folder The folder to search for.
     * @param string $base The folder to start searching from.
     * @param bool $loop Internal flag for recursion.
     *
     * @see WP_Filesystem_Base::search_for_folder()
     * @fires wpfs_before_search_for_folder_action
     * @fires wpfs_after_search_for_folder_action
     * @fires wpfs_search_for_folder_filter
     * @return string|false The location of the remote path, or false.
     */
    public function searchForFolder(string $folder, string $base = '.', bool $loop = false);
}
