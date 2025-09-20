<?php

namespace Metapraxis\WPFileSystem\Hooks;

use Metapraxis\WPFileSystem\Contracts\FSBaseReader;
use Metapraxis\WPFileSystem\Hooks\Collection\ReadingHooks;

/**
 * @extends HookableFileSystem<FSBaseReader>
 */
class HookableFSReader extends HookableFileSystem implements FSBaseReader
{
    use ReadingHooks;

    public function getHomePath(): string
    {
        do_action(self::$BEFORE_GET_HOME_PATH_ACTION);
        $result = $this->fs->getHomePath();
        $result = apply_filters(self::$GET_HOME_PATH_FILTER, $result);
        do_action(self::$AFTER_GET_HOME_PATH_ACTION, $result);

        return $result;
    }

    public function getInstallationPath(): string
    {
        do_action(self::$BEFORE_GET_INSTALLATION_PATH_ACTION);
        $result = $this->fs->getInstallationPath();
        $result = apply_filters(self::$GET_INSTALLATION_PATH_FILTER, $result);
        do_action(self::$AFTER_GET_INSTALLATION_PATH_ACTION, $result);

        return $result;
    }

    public function getContentPath(): string
    {
        do_action(self::$BEFORE_GET_CONTENT_PATH_ACTION);
        $result = $this->fs->getContentPath();
        $result = apply_filters(self::$GET_CONTENT_PATH_FILTER, $result);
        do_action(self::$AFTER_GET_CONTENT_PATH_ACTION, $result);

        return $result;
    }

    public function getPluginsPath(): string
    {
        do_action(self::$BEFORE_GET_PLUGINS_PATH_ACTION);
        $result = $this->fs->getPluginsPath();
        $result = apply_filters(self::$GET_PLUGINS_PATH_FILTER, $result);
        do_action(self::$AFTER_GET_PLUGINS_PATH_ACTION, $result);

        return $result;
    }

    public function getThemesPath($theme = false): string
    {
        do_action(self::$BEFORE_GET_THEMES_PATH_ACTION, $theme);
        $result = $this->fs->getThemesPath($theme);
        $result = apply_filters(self::$GET_THEMES_PATH_FILTER, $result, $theme);
        do_action(self::$AFTER_GET_THEMES_PATH_ACTION, $result, $theme);

        return $result;
    }

    public function getLangPath(): string
    {
        do_action(self::$BEFORE_GET_LANG_PATH_ACTION);
        $result = $this->fs->getLangPath();
        $result = apply_filters(self::$GET_LANG_PATH_FILTER, $result);
        do_action(self::$AFTER_GET_LANG_PATH_ACTION, $result);

        return $result;
    }

    public function getHumanReadablePermissions(string $file): string
    {
        do_action(self::$BEFORE_GET_HUMAN_READABLE_PERMISSIONS_ACTION, $file);
        $result = $this->fs->getHumanReadablePermissions($file);
        $result = apply_filters(self::$GET_HUMAN_READABLE_PERMISSIONS_FILTER, $result, $file);
        do_action(self::$AFTER_GET_HUMAN_READABLE_PERMISSIONS_ACTION, $result, $file);

        return $result;
    }

    public function getPermissions(string $file): string
    {
        do_action(self::$BEFORE_GET_PERMISSIONS_ACTION, $file);
        $result = $this->fs->getPermissions($file);
        $result = apply_filters(self::$GET_PERMISSIONS_FILTER, $result, $file);
        do_action(self::$AFTER_GET_PERMISSIONS_ACTION, $result, $file);

        return $result;
    }

    public function getContents(string $file)
    {
        do_action(self::$BEFORE_GET_CONTENTS_ACTION, $file);
        $result = $this->fs->getContents($file);
        $result = apply_filters(self::$GET_CONTENTS_FILTER, $result, $file);
        do_action(self::$AFTER_GET_CONTENTS_ACTION, $result, $file);

        return $result;
    }

    public function getContentsAsArray(string $file)
    {
        do_action(self::$BEFORE_GET_CONTENTS_AS_ARRAY_ACTION, $file);
        $result = $this->fs->getContentsAsArray($file);
        $result = apply_filters(self::$GET_CONTENTS_AS_ARRAY_FILTER, $result, $file);
        do_action(self::$AFTER_GET_CONTENTS_AS_ARRAY_ACTION, $result, $file);

        return $result;
    }

    public function getCurrentPath()
    {
        do_action(self::$BEFORE_GET_CURRENT_PATH_ACTION);
        $result = $this->fs->getCurrentPath();
        $result = apply_filters(self::$GET_CURRENT_PATH_FILTER, $result);
        do_action(self::$AFTER_GET_CURRENT_PATH_ACTION, $result);

        return $result;
    }

    public function getOwner(string $file)
    {
        do_action(self::$BEFORE_GET_OWNER_ACTION, $file);
        $result = $this->fs->getOwner($file);
        $result = apply_filters(self::$GET_OWNER_FILTER, $result, $file);
        do_action(self::$AFTER_GET_OWNER_ACTION, $result, $file);

        return $result;
    }

    public function getGroup(string $file)
    {
        do_action(self::$BEFORE_GET_GROUP_ACTION, $file);
        $result = $this->fs->getGroup($file);
        $result = apply_filters(self::$GET_GROUP_FILTER, $result, $file);
        do_action(self::$AFTER_GET_GROUP_ACTION, $result, $file);

        return $result;
    }

    public function getLastAccessedTime(string $file)
    {
        do_action(self::$BEFORE_GET_LAST_ACCESSED_TIME_ACTION, $file);
        $result = $this->fs->getLastAccessedTime($file);
        $result = apply_filters(self::$GET_LAST_ACCESSED_TIME_FILTER, $result, $file);
        do_action(self::$AFTER_GET_LAST_ACCESSED_TIME_ACTION, $result, $file);

        return $result;
    }

    public function getLastModifiedTime(string $file)
    {
        do_action(self::$BEFORE_GET_LAST_MODIFIED_TIME_ACTION, $file);
        $result = $this->fs->getLastModifiedTime($file);
        $result = apply_filters(self::$GET_LAST_MODIFIED_TIME_FILTER, $result, $file);
        do_action(self::$AFTER_GET_LAST_MODIFIED_TIME_ACTION, $result, $file);

        return $result;
    }

    public function getFileSize(string $file)
    {
        do_action(self::$BEFORE_GET_FILE_SIZE_ACTION, $file);
        $result = $this->fs->getFileSize($file);
        $result = apply_filters(self::$GET_FILE_SIZE_FILTER, $result, $file);
        do_action(self::$AFTER_GET_FILE_SIZE_ACTION, $result, $file);

        return $result;
    }

    public function getPermissionsAsOctal(string $mode): string
    {
        do_action(self::$BEFORE_GET_PERMISSIONS_AS_OCTAL_ACTION, $mode);
        $result = $this->fs->getPermissionsAsOctal($mode);
        $result = apply_filters(self::$GET_PERMISSIONS_AS_OCTAL_FILTER, $result, $mode);
        do_action(self::$AFTER_GET_PERMISSIONS_AS_OCTAL_ACTION, $result, $mode);

        return $result;
    }

    public function getDirectoryList(string $path, bool $includeHidden = true, bool $recursive = false)
    {
        do_action(self::$BEFORE_GET_DIRECTORY_LIST_ACTION, $path, $includeHidden, $recursive);
        $result = $this->fs->getDirectoryList($path, $includeHidden, $recursive);
        $result = apply_filters(self::$GET_DIRECTORY_LIST_FILTER, $result, $path, $includeHidden, $recursive);
        do_action(self::$AFTER_GET_DIRECTORY_LIST_ACTION, $result, $path, $includeHidden, $recursive);

        return $result;
    }

    public function getFiles(
        string $folder = '',
        int $levels = 100,
        array $exclusions = [],
        bool $includeHidden = false
    ) {
        do_action(self::$BEFORE_GET_FILES_ACTION, $folder, $levels, $exclusions, $includeHidden);
        $result = $this->fs->getFiles($folder, $levels, $exclusions, $includeHidden);
        $result = apply_filters(self::$GET_FILES_FILTER, $result, $folder, $levels, $exclusions, $includeHidden);
        do_action(self::$AFTER_GET_FILES_ACTION, $result, $folder, $levels, $exclusions, $includeHidden);

        return $result;
    }

    public function getUploadsDirInfo(?string $time = null, bool $create_dir = true, bool $refresh_cache = false): array
    {
        do_action(self::$BEFORE_GET_UPLOADS_DIR_INFO_ACTION, $time, $create_dir, $refresh_cache);
        $result = $this->fs->getUploadsDirInfo($time, $create_dir, $refresh_cache);
        $result = apply_filters(self::$GET_UPLOADS_DIR_INFO_FILTER, $result, $time, $create_dir, $refresh_cache);
        do_action(self::$AFTER_GET_UPLOADS_DIR_INFO_ACTION, $result, $time, $create_dir, $refresh_cache);

        return $result;
    }

    public function getTempDir(): string
    {
        do_action(self::$BEFORE_GET_TEMP_DIR_ACTION);
        $result = $this->fs->getTempDir();
        $result = apply_filters(self::$GET_TEMP_DIR_FILTER, $result);
        do_action(self::$AFTER_GET_TEMP_DIR_ACTION, $result);

        return $result;
    }

    public function getNormalizePath(string $path): string
    {
        do_action(self::$BEFORE_GET_NORMALIZE_PATH_ACTION, $path);
        $result = $this->fs->getNormalizePath($path);
        $result = apply_filters(self::$GET_NORMALIZE_PATH_FILTER, $result, $path);
        do_action(self::$AFTER_GET_NORMALIZE_PATH_ACTION, $result, $path);

        return $result;
    }

    public function getSanitizeFilename(string $filename): string
    {
        do_action(self::$BEFORE_GET_SANITIZE_FILENAME_ACTION, $filename);
        $result = $this->fs->getSanitizeFilename($filename);
        $result = apply_filters(self::$GET_SANITIZE_FILENAME_FILTER, $result, $filename);
        do_action(self::$AFTER_GET_SANITIZE_FILENAME_ACTION, $result, $filename);

        return $result;
    }

    public function findFolder(string $folder)
    {
        do_action(self::$BEFORE_FIND_FOLDER_ACTION, $folder);
        $result = $this->fs->findFolder($folder);
        $result = apply_filters(self::$FIND_FOLDER_FILTER, $result, $folder);
        do_action(self::$AFTER_FIND_FOLDER_ACTION, $result, $folder);

        return $result;
    }

    public function searchForFolder(string $folder, string $base = '.', bool $loop = false)
    {
        do_action(self::$BEFORE_SEARCH_FOR_FOLDER_ACTION, $folder, $base, $loop);
        $result = $this->fs->searchForFolder($folder, $base, $loop);
        $result = apply_filters(self::$SEARCH_FOR_FOLDER_FILTER, $result, $folder, $base, $loop);
        do_action(self::$AFTER_SEARCH_FOR_FOLDER_ACTION, $result, $folder, $base, $loop);

        return $result;
    }
}
