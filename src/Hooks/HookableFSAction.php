<?php

namespace Metapraxis\WPFileSystem\Hooks;

use Metapraxis\WPFileSystem\Contracts\FSBaseAction;
use Metapraxis\WPFileSystem\Hooks\Collection\ActionHooks;

/**
 * @extends HookableFileSystem<FSBaseAction>
 */
class HookableFSAction extends HookableFileSystem implements FSBaseAction
{
    use ActionHooks;

    public function putContents(string $file, string $contents, $mode = false): bool
    {
        do_action(self::$BEFORE_PUT_CONTENTS_ACTION, $file, $contents, $mode);
        $result = $this->fs->putContents($file, $contents, $mode);
        do_action(self::$AFTER_PUT_CONTENTS_ACTION, $result, $file, $contents, $mode);

        return $result;
    }

    public function copyFile(string $source, string $destination, bool $overwrite = false, $mode = false): bool
    {
        do_action(self::$BEFORE_COPY_FILE_ACTION, $source, $destination, $overwrite, $mode);
        $result = $this->fs->copyFile($source, $destination, $overwrite, $mode);
        do_action(self::$AFTER_COPY_FILE_ACTION, $result, $source, $destination, $overwrite, $mode);

        return $result;
    }

    public function copyDirectory(string $from, string $to, array $skipList = [])
    {
        do_action(self::$BEFORE_COPY_DIRECTORY_ACTION, $from, $to, $skipList);
        $result = $this->fs->copyDirectory($from, $to, $skipList);
        do_action(self::$AFTER_COPY_DIRECTORY_ACTION, $result, $from, $to, $skipList);

        return $result;
    }

    public function moveFile(string $source, string $destination, bool $overwrite = false): bool
    {
        do_action(self::$BEFORE_MOVE_FILE_ACTION, $source, $destination, $overwrite);
        $result = $this->fs->moveFile($source, $destination, $overwrite);
        do_action(self::$AFTER_MOVE_FILE_ACTION, $result, $source, $destination, $overwrite);

        return $result;
    }

    public function moveDirectory(string $from, string $to, bool $overwrite = false)
    {
        do_action(self::$BEFORE_MOVE_DIRECTORY_ACTION, $from, $to, $overwrite);
        $result = $this->fs->moveDirectory($from, $to, $overwrite);
        do_action(self::$AFTER_MOVE_DIRECTORY_ACTION, $result, $from, $to, $overwrite);

        return $result;
    }

    public function delete(string $path, bool $recursive = false, $type = false): bool
    {
        do_action(self::$BEFORE_DELETE_ACTION, $path, $recursive, $type);
        $result = $this->fs->delete($path, $recursive, $type);
        do_action(self::$AFTER_DELETE_ACTION, $result, $path, $recursive, $type);

        return $result;
    }

    public function touch(string $file, int $mtime = 0, int $atime = 0): bool
    {
        do_action(self::$BEFORE_TOUCH_ACTION, $file, $mtime, $atime);
        $result = $this->fs->touch($file, $mtime, $atime);
        do_action(self::$AFTER_TOUCH_ACTION, $result, $file, $mtime, $atime);

        return $result;
    }

    public function createDirectory(string $path, $chmod = false, $chown = false, $chgrp = false): bool
    {
        do_action(self::$BEFORE_CREATE_DIRECTORY_ACTION, $path, $chmod, $chown, $chgrp);
        $result = $this->fs->createDirectory($path, $chmod, $chown, $chgrp);
        do_action(self::$AFTER_CREATE_DIRECTORY_ACTION, $result, $path, $chmod, $chown, $chgrp);

        return $result;
    }

    public function createTempFile(string $filename = '', string $dir = ''): string
    {
        do_action(self::$BEFORE_CREATE_TEMP_FILE_ACTION, $filename, $dir);
        $result = $this->fs->createTempFile($filename, $dir);
        $result = apply_filters(self::$CREATE_TEMP_FILE_FILTER, $result, $filename, $dir);
        do_action(self::$AFTER_CREATE_TEMP_FILE_ACTION, $result, $filename, $dir);

        return $result;
    }

    public function deleteDirectory(string $path, bool $recursive = false): bool
    {
        do_action(self::$BEFORE_DELETE_DIRECTORY_ACTION, $path, $recursive);
        $result = $this->fs->deleteDirectory($path, $recursive);
        do_action(self::$AFTER_DELETE_DIRECTORY_ACTION, $result, $path, $recursive);

        return $result;
    }

    public function handleUpload(array &$file, $overrides = false, string $time = null): array
    {
        do_action(self::$BEFORE_HANDLE_UPLOAD_ACTION, $file, $overrides, $time);
        $result = $this->fs->handleUpload($file, $overrides, $time);
        $result = apply_filters(self::$HANDLE_UPLOAD_FILTER, $result, $file, $overrides, $time);
        do_action(self::$AFTER_HANDLE_UPLOAD_ACTION, $result, $file, $overrides, $time);

        return $result;
    }

    public function handleSideload(array &$file, $overrides = false, string $time = null): array
    {
        do_action(self::$BEFORE_HANDLE_SIDELOAD_ACTION, $file, $overrides, $time);
        $result = $this->fs->handleSideload($file, $overrides, $time);
        $result = apply_filters(self::$HANDLE_SIDELOAD_FILTER, $result, $file, $overrides, $time);
        do_action(self::$AFTER_HANDLE_SIDELOAD_ACTION, $result, $file, $overrides, $time);

        return $result;
    }

    public function downloadFromUrl(string $url, int $timeout = 300, bool $signatureVerification = false)
    {
        do_action(self::$BEFORE_DOWNLOAD_FROM_URL_ACTION, $url, $timeout, $signatureVerification);
        $result = $this->fs->downloadFromUrl($url, $timeout, $signatureVerification);
        $result = apply_filters(self::$DOWNLOAD_FROM_URL_FILTER, $result, $url, $timeout, $signatureVerification);
        do_action(self::$AFTER_DOWNLOAD_FROM_URL_ACTION, $result, $url, $timeout, $signatureVerification);

        return $result;
    }

    public function unzip(string $file, string $to)
    {
        do_action(self::$BEFORE_UNZIP_ACTION, $file, $to);
        $result = $this->fs->unzip($file, $to);
        do_action(self::$AFTER_UNZIP_ACTION, $result, $file, $to);

        return $result;
    }
}
