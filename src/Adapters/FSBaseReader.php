<?php

namespace Metapraxis\WPFileSystem\Adapters;

use Metapraxis\WPFileSystem\Contracts\FSBaseReader as BaseReader;

/**
 * @see tests/Unit/FSBaseReaderTest.php
 */
class FSBaseReader extends FileSystem implements BaseReader
{
    public function getHomePath(): string
    {
        return get_home_path();
    }

    public function getInstallationPath(): string
    {
        return $this->wp_filesystem->abspath();
    }

    public function getContentPath(): string
    {
        return $this->wp_filesystem->wp_content_dir();
    }

    public function getPluginsPath(): string
    {
        return $this->wp_filesystem->wp_plugins_dir();
    }

    public function getThemesPath($theme = false): string
    {
        return $this->wp_filesystem->wp_themes_dir($theme);
    }

    public function getLangPath(): string
    {
        return $this->wp_filesystem->wp_lang_dir();
    }

    public function getHumanReadablePermissions(string $file): string
    {
        return $this->wp_filesystem->gethchmod($file);
    }

    public function getPermissions(string $file): string
    {
        return $this->wp_filesystem->getchmod($file);
    }

    public function getContents(string $file)
    {
        return $this->wp_filesystem->get_contents($file);
    }

    public function getContentsAsArray(string $file)
    {
        return $this->wp_filesystem->get_contents_array($file);
    }

    public function getCurrentPath()
    {
        return $this->wp_filesystem->cwd();
    }

    public function getOwner(string $file)
    {
        return $this->wp_filesystem->owner($file);
    }

    public function getGroup(string $file)
    {
        return $this->wp_filesystem->group($file);
    }

    public function getLastAccessedTime(string $file)
    {
        return $this->wp_filesystem->atime($file);
    }

    public function getLastModifiedTime(string $file)
    {
        return $this->wp_filesystem->mtime($file);
    }

    public function getFileSize(string $file)
    {
        return $this->wp_filesystem->size($file);
    }

    public function getPermissionsAsOctal(string $mode): string
    {
        return $this->wp_filesystem->getnumchmodfromh($mode);
    }

    public function getDirectoryList(string $path, bool $includeHidden = true, bool $recursive = false)
    {
        return $this->wp_filesystem->dirlist($path, $includeHidden, $recursive);
    }

    public function getFiles(
        string $folder = '',
        int $levels = 100,
        array $exclusions = [],
        bool $includeHidden = false
    ) {
        return list_files($folder, $levels, $exclusions, $includeHidden);
    }

    public function getUploadsDirInfo(?string $time = null, bool $create_dir = true, bool $refresh_cache = false): array
    {
        return wp_upload_dir($time, $create_dir, $refresh_cache);
    }

    public function getTempDir(): string
    {
        return get_temp_dir();
    }

    public function getNormalizePath(string $path): string
    {
        return wp_normalize_path($path);
    }

    public function getSanitizeFilename(string $filename): string
    {
        return sanitize_file_name($filename);
    }

    public function findFolder(string $folder)
    {
        return $this->wp_filesystem->find_folder($folder);
    }

    public function searchForFolder(string $folder, string $base = '.', bool $loop = false)
    {
        return $this->wp_filesystem->search_for_folder($folder, $base, $loop);
    }
}
