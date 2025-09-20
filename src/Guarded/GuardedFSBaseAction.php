<?php

namespace Metapraxis\WPFileSystem\Guarded;

use Metapraxis\WPFileSystem\Assertion\Assertion;
use Metapraxis\WPFileSystem\Contracts\FSBaseAction;
use Metapraxis\WPFileSystem\Exceptions\FSException;
use Metapraxis\WPFileSystem\Exceptions\FSPathNotFoundException;
use Metapraxis\WPFileSystem\Exceptions\FSPermissionException;

/**
 * @extends GuardedFileSystem<FSBaseAction>
 */
class GuardedFSBaseAction extends GuardedFileSystem implements FSBaseAction
{
    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function putContents(string $file, string $contents, $mode = false): bool
    {
        return Assertion::validateResourceOperation($this->fs->putContents($file, $contents, $mode), $file);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function copyFile(string $source, string $destination, bool $overwrite = false, $mode = false): bool
    {
        Assertion::ensureResourceExists($source);

        return Assertion::validateResourceOperation(
            $this->fs->copyFile($source, $destination, $overwrite, $mode),
            $source
        );
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function copyDirectory(string $from, string $to, array $skipList = [])
    {
        Assertion::ensureResourceExists($to);

        return Assertion::validateResourceOperation($this->fs->copyDirectory($from, $to, $skipList), $from);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function moveFile(string $source, string $destination, bool $overwrite = false): bool
    {
        Assertion::ensureResourceExists($destination);

        return Assertion::validateResourceOperation($this->fs->moveFile($source, $destination, $overwrite), $source);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function moveDirectory(string $from, string $to, bool $overwrite = false)
    {
        Assertion::ensureResourceExists($to);

        return Assertion::validateResourceOperation($this->fs->moveDirectory($from, $to, $overwrite), $from);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function delete(string $path, bool $recursive = false, $type = false): bool
    {
        return Assertion::validateResourceOperation($this->fs->delete($path, $recursive, $type), $path);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function touch(string $file, int $mtime = 0, int $atime = 0): bool
    {
        return Assertion::validateResourceOperation($this->fs->touch($file, $mtime, $atime), $file);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function createDirectory(string $path, $chmod = false, $chown = false, $chgrp = false): bool
    {
        return Assertion::validateResourceOperation($this->fs->createDirectory($path, $chmod, $chown, $chgrp), $path);
    }

    /**
     * @throws FSException
     */
    public function createTempFile(string $filename = '', string $dir = ''): string
    {
        return Assertion::ensureSuccessful($this->fs->createTempFile($filename, $dir));
    }

    /**
     * @throws FSPermissionException
     * @throws FSPathNotFoundException
     * @throws FSException
     */
    public function deleteDirectory(string $path, bool $recursive = false): bool
    {
        return Assertion::validateResourceOperation($this->fs->deleteDirectory($path, $recursive), $path);
    }

    /**
     * @throws FSException
     */
    public function handleUpload(array &$file, $overrides = false, string $time = null): array
    {
        return Assertion::ensureSuccessful($this->fs->handleUpload($file, $overrides, $time));
    }

    /**
     * @throws FSException
     */
    public function handleSideload(array &$file, $overrides = false, string $time = null): array
    {
        return Assertion::ensureSuccessful($this->fs->handleSideload($file, $overrides, $time));
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function downloadFromUrl(string $url, int $timeout = 300, bool $signatureVerification = false)
    {
        return Assertion::validateResourceOperation(
            $this->fs->downloadFromUrl($url, $timeout, $signatureVerification),
            $url
        );
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function unzip(string $file, string $to)
    {
        return Assertion::validateResourceOperation($this->fs->unzip($file, $to), $file);
    }
}
