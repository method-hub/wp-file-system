<?php

namespace Metapraxis\WPFileSystem\Adapters;

use Metapraxis\WPFileSystem\Contracts\FSBaseAction as BaseAction;

/**
 * @see tests/Unit/FSBaseActionTest.php
 */
class FSBaseAction extends FileSystem implements BaseAction
{
    public function putContents(string $file, string $contents, $mode = false): bool
    {
        return $this->wp_filesystem->put_contents($file, $contents, $mode);
    }

    public function copyFile(string $source, string $destination, bool $overwrite = false, $mode = false): bool
    {
        return $this->wp_filesystem->copy($source, $destination, $overwrite, $mode);
    }

    public function copyDirectory(string $from, string $to, array $skipList = [])
    {
        return copy_dir($from, $to, $skipList);
    }

    public function moveFile(string $source, string $destination, bool $overwrite = false): bool
    {
        return $this->wp_filesystem->move($source, $destination, $overwrite);
    }

    public function moveDirectory(string $from, string $to, bool $overwrite = false)
    {
        return move_dir($from, $to, $overwrite);
    }

    public function delete(string $path, bool $recursive = false, $type = false): bool
    {
        return $this->wp_filesystem->delete($path, $recursive, $type);
    }

    public function touch(string $file, int $mtime = 0, int $atime = 0): bool
    {
        return $this->wp_filesystem->touch($file, $mtime, $atime);
    }

    public function createDirectory(string $path, $chmod = false, $chown = false, $chgrp = false): bool
    {
        return $this->wp_filesystem->mkdir($path, $chmod, $chown, $chgrp);
    }

    public function createTempFile(string $filename = '', string $dir = ''): string
    {
        return wp_tempnam($filename, $dir);
    }

    public function deleteDirectory(string $path, bool $recursive = false): bool
    {
        return $this->wp_filesystem->rmdir($path, $recursive);
    }

    public function handleUpload(array &$file, $overrides = false, string $time = null): array
    {
        return wp_handle_upload($file, $overrides, $time);
    }

    public function handleSideload(array &$file, $overrides = false, string $time = null): array
    {
        return wp_handle_sideload($file, $overrides, $time);
    }

    public function downloadFromUrl(string $url, int $timeout = 300, bool $signatureVerification = false)
    {
        return download_url($url, $timeout, $signatureVerification);
    }

    public function unzip(string $file, string $to)
    {
        return unzip_file($file, $to);
    }
}
