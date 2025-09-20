<?php

namespace Metapraxis\WPFileSystem\Hooks;

use Metapraxis\WPFileSystem\Contracts\FSBaseAuditor;
use Metapraxis\WPFileSystem\Hooks\Collection\AuditorHooks;

/**
 * @extends HookableFileSystem<FSBaseAuditor>
 */
class HookableFSAuditor extends HookableFileSystem implements FSBaseAuditor
{
    use AuditorHooks;

    public function exists(string $path): bool
    {
        do_action(self::$BEFORE_EXISTS_ACTION, $path);
        $result = $this->fs->exists($path);
        do_action(self::$AFTER_EXISTS_ACTION, $result, $path);

        return $result;
    }

    public function isBinary(string $text): bool
    {
        do_action(self::$BEFORE_IS_BINARY_ACTION, $text);
        $result = $this->fs->isBinary($text);
        do_action(self::$AFTER_IS_BINARY_ACTION, $result, $text);

        return $result;
    }

    public function isFile(string $path): bool
    {
        do_action(self::$BEFORE_IS_FILE_ACTION, $path);
        $result = $this->fs->isFile($path);
        do_action(self::$AFTER_IS_FILE_ACTION, $result, $path);

        return $result;
    }

    public function isDirectory(string $path): bool
    {
        do_action(self::$BEFORE_IS_DIRECTORY_ACTION, $path);
        $result = $this->fs->isDirectory($path);
        do_action(self::$AFTER_IS_DIRECTORY_ACTION, $result, $path);

        return $result;
    }

    public function isReadable(string $path): bool
    {
        do_action(self::$BEFORE_IS_READABLE_ACTION, $path);
        $result = $this->fs->isReadable($path);
        do_action(self::$AFTER_IS_READABLE_ACTION, $result, $path);

        return $result;
    }

    public function isWritable(string $path): bool
    {
        do_action(self::$BEFORE_IS_WRITABLE_ACTION, $path);
        $result = $this->fs->isWritable($path);
        do_action(self::$AFTER_IS_WRITABLE_ACTION, $result, $path);

        return $result;
    }

    public function connect(): bool
    {
        do_action(self::$BEFORE_CONNECT_ACTION);
        $result = $this->fs->connect();
        do_action(self::$AFTER_CONNECT_ACTION, $result);

        return $result;
    }

    public function verifyMd5(string $filename, string $expectedMd5)
    {
        do_action(self::$BEFORE_VERIFY_MD5_ACTION, $filename, $expectedMd5);
        $result = $this->fs->verifyMd5($filename, $expectedMd5);
        do_action(self::$AFTER_VERIFY_MD5_ACTION, $result, $filename, $expectedMd5);

        return $result;
    }

    public function verifySignature(string $filename, $signatures, $filenameForErrors = false)
    {
        do_action(self::$BEFORE_VERIFY_SIGNATURE_ACTION, $filename, $signatures, $filenameForErrors);
        $result = $this->fs->verifySignature($filename, $signatures, $filenameForErrors);
        do_action(self::$AFTER_VERIFY_SIGNATURE_ACTION, $result, $filename, $signatures, $filenameForErrors);

        return $result;
    }

    public function isZipFile(string $file): bool
    {
        do_action(self::$BEFORE_IS_ZIP_FILE_ACTION, $file);
        $result = $this->fs->isZipFile($file);
        do_action(self::$AFTER_IS_ZIP_FILE_ACTION, $result, $file);

        return $result;
    }
}
